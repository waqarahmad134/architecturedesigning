<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super admin user
        $email = 'admin@gmail.com';
        $password = 'password';

        $user = User::where('email',$email)->get();
        if($user->count() > 0){
            $this->command->info($email . ' Already in records! Email: '.$email.' - password :'.$password);
        }
        else{
            User::create([
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'role' => 'admin',
                'email' => $email,
                'password' => bcrypt($password),
            ]);

            $this->command->info('Super admin seeded successfully with Email: '.$email.' - password :'.$password);
        }
    }
}
