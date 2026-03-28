<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Newsletter;
use App\Mail\WelcomeNewsletter;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomNewsletter;

class NewsletterController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletters,email'
        ], [
            'email.unique' => 'This address is already subscribed!'
        ]);

        $subscriber = Newsletter::create($validated);
        
        Mail::to($subscriber->email)->queue(new WelcomeNewsletter());

        return back()->with('message', 'The letter has been sent!');
    }

    
    public function sendBroadcast(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $count = Newsletter::count();

        Newsletter::chunk(100, function ($subscribers) use ($request) {
            foreach ($subscribers as $subscriber) {
                Mail::to($subscriber->email)
                    ->queue(new CustomNewsletter($request->subject, $request->message));
            }
        });

        return back()->with('success', "The mailing list has been launched! Emails in the queue: {$count}");
    }
}
