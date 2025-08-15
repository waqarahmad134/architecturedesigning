<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;


class ContactMessageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Thank you! Your message has been sent.');
    }

    public function contact()
    {
        return view('contact');
    }

    public function index()
    {
        $messages = ContactMessage::latest()->paginate(10);
        return view('contact', compact('messages'));
    }

    public function destroy($id)
    {
        ContactMessage::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Message deleted successfully.');
    }
}
