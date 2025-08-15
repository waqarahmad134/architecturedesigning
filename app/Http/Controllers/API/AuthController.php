<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeMyPasswordRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LoginSuperadminRequest;
use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\VerifyUpdateEmailRequest;
use App\Mail\SendSecretCodeMail;
use App\Models\LoginHistory;
use App\Models\SecretCode;
use App\Models\SecretLoginCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\Token;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        $credentials = $request->only('email', 'password');

        if(isset($request->twofa)){
            return response()->json([
                'status' => true,
                'data' => "",
                'error' => "",
                'message' => "OTP Sent"
            ], 200);
        }
        else if (Auth::attempt($credentials)) {
            $user = Auth::user();

            //create login history
           $login_history = new LoginHistory;
           $login_history->user_id = $user->id;
           $login_history->save();

            $token = $user->createToken('MyApp')->accessToken;

            $data = [
                'token' => $token,
                'user' => $user
            ];
            return response()->json([
                'status' => true,
                'data' => $data,
                'error' => "",
                'message' => "User Logged In!"
            ]);
        }

        return response()->json([
            'status' => false,
            'data' => "",
            'error' => "Unauthorized",
            'message' => "Credentials Mismatched"
        ], 200);
    }

    public function send_code_to_me($request){
        $secret_code = Hash::make(now());
        $user = User::where("email",$request->email)->first();

            // save code for future use
            $code = new SecretLoginCode();
            $code->code = $secret_code;
            $code->email = $request->email;
            $code->expiry_at = now()->addHours(3); // the secret code will be expired after three hours
            $code->save();

            // send email with secret code to change password
            $data = [
                'name' => $user->first_name . " " . $user->last_name,
                'email' => $request->email,
                'code' => $secret_code
            ];

            Mail::send('mails.sendSecretCode', $data, function ($message) use ($data) {
                $message->to($data['email'], $data['name'])->subject('Confirm Your Login Email');
            });
    }

    public function simple_login($email, $password){
        if (Auth::attempt(["email" => $email, "password" => $password])) {
            $user = Auth::user();
            //create login history
            $login_history = new LoginHistory;
            $login_history->user_id = $user->id;
            $login_history->save();

            $token = $user->createToken('MyApp')->accessToken;

            $data = [
                'token' => $token,
                'user' => $user
            ];
            return response()->json([
                'status' => true,
                'data' => $data,
                'error' => "",
                'message' => "User Logged In!"
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'data' => "",
                'error' => "Unauthorized",
                'message' => "Credentials Mismatched"
            ], 200);
        }
    }

    public function twofa_check($request){
        if(isset($request->code)){
            if($this->check_login_code($request->code, $request->email)){
                return $this->simple_login($request->email, $request->password);
            }
            else{
                return response()->json([
                    'status' => true,
                    'data' => "",
                    'error' => "",
                    'message' => "Code mismatched or expired"
                ], 200);
            }
        }
        else{
            $this->send_code_to_me($request);
            return response()->json([
                'status' => true,
                'data' => "",
                'error' => "",
                'message' => "OTP Sent"
            ], 200);
        }
    }

    public function loginSuperadmin(LoginRequest $request){
        $credentials = $request->only('email', 'password');
        if($request->twofa == "true"){
            return $this->twofa_check($request);
        }
        else if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if($user->role == 'superadmin'){
                //create login history
                $login_history = new LoginHistory;
                $login_history->user_id = $user->id;
                $login_history->save();

                    $token = $user->createToken('MyApp')->accessToken;

                    $data = [
                        'token' => $token,
                        'user' => $user
                    ];
                    return response()->json([
                        'status' => true,
                        'data' => $data,
                        'error' => "",
                        'message' => "User Logged In!"
                    ]);
            }
            else{
                return response()->json([
                    'status' => false,
                    'data' => "",
                    'error' => "Unauthorized",
                    'message' => "Unauthorized to login"
                ], 200);
            }
        }

        return response()->json([
            'status' => false,
            'data' => "",
            'error' => "Unauthorized",
            'message' => "Credentials Mismatched"
        ], 200);
    }

    public function loginSubadmin(LoginRequest $request){
        $credentials = $request->only('email', 'password');
        if($request->twofa == "true"){
            return $this->twofa_check($request);
        }
        else if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if($user->role == 'subadmin'){
                //create login history
                $login_history = new LoginHistory;
                $login_history->user_id = $user->id;
                $login_history->save();

                    $token = $user->createToken('MyApp')->accessToken;

                    $data = [
                        'token' => $token,
                        'user' => $user
                    ];
                    return response()->json([
                        'status' => true,
                        'data' => $data,
                        'error' => "",
                        'message' => "User Logged In!"
                    ]);
            }
            else{
                return response()->json([
                    'status' => false,
                    'data' => "",
                    'error' => "Unauthorized",
                    'message' => "Unauthorized to login"
                ], 200);
            }
        }

        return response()->json([
            'status' => false,
            'data' => "",
            'error' => "Unauthorized",
            'message' => "Credentials Mismatched"
        ], 200);
    }

    public function logout(){
        $accessToken = auth()->user()->token();

        if (Token::find($accessToken->id)->revoke()) {
            return response()->json([
                'status' => true,
                'data' => "",
                'error' => "",
                'message' => "User Logged Out!"
            ]);
        }
        else {
            return response()->json([
                'status' => false,
                'data' => "",
                'error' => "Something went wrong",
                'message' => "Something went wrong"
            ]);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request){
        $user_email = User::where("email",$request->email)->first();
        if($user_email){
            $secret_code = Hash::make(now());

            // save code for future use
            $code = new SecretCode();
            $code->code = $secret_code;
            $code->email = $request->email;
            $code->expiry_at = now()->addHours(3); // the secret code will be expired after three hours
            $code->save();

            // send email with secret code to change password
            $data = [
                'name' => $user_email->first_name . " " . $user_email->last_name,
                'email' => $request->email,
                'code' => $secret_code
            ];

            Mail::send('mails.sendSecretCode', $data, function ($message) use ($data) {
                $message->to($data['email'], $data['name'])->subject('Confirm Your Email');
            });
        }
        return response()->json([
            'status' => true,
            'data' => "",
            'error' => "",
            'message' => "If record found mail will be sent!"
        ]);
    }

    public function updateEmail(UpdateEmailRequest $request){
        $user = User::where("id",Auth::user()->id)->first();

        $secret_code = Hash::make(now());

        // save code for future use
        $code = new SecretCode();
        $code->code = $secret_code;
        $code->email = $request->email;
        $code->expiry_at = now()->addHours(3); // the secret code will be expired after three hours
        $code->save();

        // send email with secret code to change password
        $data = [
            'name' => $user->first_name . " " . $user->last_name,
            'email' => $request->email,
            'code' => $secret_code
        ];

        Mail::send('mails.sendSecretCode', $data, function ($message) use ($data) {
            $message->to($data['email'], $data['name'])->subject('Confirm Your Email');
        });
        return response()->json([
            'status' => true,
            'data' => "",
            'error' => "",
            'message' => "Verification Email Sent!"
        ]);
    }

    public function check_login_code($code, $email){
        $code_check = SecretLoginCode::where('code',$code)->where('email',$email)->first();
        return $code_check != null ? true : false;
    }

    public function changePassword(ChangePasswordRequest $request){
        $new_password =  Hash::make($request->password);

        $code_check = SecretCode::where('code',$request->code)->first();

        if($code_check->expiry_at > now()){
            $user = User::where('email',$request->email)->first();
            $user->password = $new_password;

            if($user->save()){
                return response()->json([
                    'status' => true,
                    'data' => "",
                    'error' => "",
                    'message' => "Password Changed!"
                ]);
            }
        }
        else{
            return response()->json([
                'status' => false,
                'data' => "",
                'error' => "",
                'message' => "Code Expired"
            ]);
        }


    }

    public function verifyUpdateEmail(VerifyUpdateEmailRequest $request){

        $code_check = SecretCode::where('code',$request->code)->first();

        if($code_check->expiry_at > now()){
            $user = User::where('id',Auth::user()->id)->first();
            $user->email = $request->email;
            $user->email_verified_at = Carbon::now();

            if($user->save()){
                return response()->json([
                    'status' => true,
                    'data' => "",
                    'error' => "",
                    'message' => "Email Changed!"
                ]);
            }
        }
        else{
            return response()->json([
                'status' => false,
                'data' => "",
                'error' => "",
                'message' => "Code Expired"
            ]);
        }


    }

    public function changeMyPassword(ChangeMyPasswordRequest $request){
        $user = User::where('id',Auth::user()->id)->first();


        if(Hash::check($request->old_password,$user->password)){
            $hashed_password =  Hash::make($request->new_password);
            $data = [
                'id' => $request->id,
                'password' => $hashed_password
            ];

            if($user->update($data)){
                return response()->json([
                    'status' => true,
                    'data' => "",
                    'error' => "",
                    'message' => "Password Changed!"
                ]);
            }
            else {
                return response()->json([
                    'status' => false,
                    'data' => "",
                    'error' => "",
                    'message' => "Code Expired"
                ]);
            }
        }
        else{
            return response()->json([
                'status' => false,
                'data' => "",
                'error' => "",
                'message' => "Password not matched with database"
            ]);
        }
    }
}
