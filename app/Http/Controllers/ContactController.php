<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email',
            'message' => 'required|string|max:500',
        ]);


        try {
            Mail::to('maxandreev27@gmail.com')->send(new ContactFormMail($validated));

            return redirect()->back()->with('success', 'Message sent successfully!');
        } catch (Exception $e) {
            \Log::error('Помилка надсилання контактної форми: ' . $e->getMessage(), [
                'email' => $validated['email']
            ]);
            return redirect()->back()->with('error', 'An error occurred!');
        }
    }
}
