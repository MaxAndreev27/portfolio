<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email',
            'message' => 'required|string|max:500',
        ]);

        // Here you can add your logic to save to database or send email
        // For example:
        // Contact::create($validated);
        // or
        // Mail::to('your-email@example.com')->send(new ContactFormMail($validated));

        return redirect()->back()->with('success', 'Message sent successfully!');
//        return redirect()->back()->with('error', 'An error occurred!');

    }
}
