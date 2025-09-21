<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use App\Models\Feedback;

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
    
public function submitFeedback(Request $request)
{
   

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'nullable|string|max:255',
        'message' => 'required|string',
    ]);

    Feedback::create([
        'user_id' => auth()->id(),
        'name' => $request->name,
        'email' => $request->email,
        'subject' => $request->subject,
        'message' => $request->message,
    ]);

    if($request->wantsJson()){
        return response()->json(['success' => 'Your feedback has been submitted successfully!']);
    }

    return redirect()->back()->with('success', 'Your feedback has been submitted successfully!');
}

public function viewfeedbacks()
{
    // Get latest feedbacks with user relationship
    $feedbacks = \App\Models\Feedback::with('user')->latest()->paginate(10);

    return view('admin.adminblade.feedback', compact('feedbacks'));
}

//delete feedback
public function deleteFeedback($id)
{
    $feedback = \App\Models\Feedback::findOrFail($id);
    $feedback->delete();

    return redirect()->back()->with('success', 'Feedback deleted successfully.');
}

public function viewSingleFeedback($id)
{
    $feedback = \App\Models\Feedback::with('user')->findOrFail($id);
    return view('admin.adminblade.feedbackview', compact('feedback'));
}



//reply feedback
public function replyFeedback(Request $request, $feedbackId)
{
    $request->validate([
        'reply' => 'required|string|max:2000',
    ]);

    $feedback = \App\Models\Feedback::findOrFail($feedbackId);

        $reply = $feedback->replies()->create([
            'user_id' => auth()->id(), // admin
            'reply' => $request->reply,
            'is_admin' => 1,           // ⚠️ mark as admin
        ]);


    // Notify **only the user who submitted the feedback**
$feedback->user->notify(new \App\Notifications\FeedbackRepliedNotification($reply));

    return response()->json(['success' => 'Reply sent successfully!']);
}

public function showFeedbackReply($feedbackId)
{
    $feedback = \App\Models\Feedback::findOrFail($feedbackId);

    // Get the latest admin reply
    $reply = $feedback->replies()->where('is_admin', 1)->latest()->first();

    return view('user.feedback_reply_popup', compact('reply'));
}



}