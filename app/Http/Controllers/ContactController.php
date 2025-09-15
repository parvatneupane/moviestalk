<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function index()
    {
        $faqs = [
            [
                'question' => 'How do I create an account?',
                'answer' => 'Click on the "Sign Up" button in the top right corner, fill in your details, and verify your email address.'
            ],
            [
                'question' => 'How can I reset my password?',
                'answer' => 'Go to the login page and click "Forgot Password". Enter your email address to receive reset instructions.'
            ],
            [
                'question' => 'How are movie ratings calculated?',
                'answer' => 'Our ratings are based on a combination of critic reviews and user ratings, weighted to provide a balanced score.'
            ],
            [
                'question' => 'Can I suggest a movie to be added?',
                'answer' => 'Yes! We welcome movie suggestions. Please use our contact form to send us your recommendations.'
            ]
        ];
        
        return view('contact', compact('faqs'));
    }
    
    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string'
        ]);
        
        // Send email
        Mail::to('bhandarishiva318@gmail.com');
        
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}