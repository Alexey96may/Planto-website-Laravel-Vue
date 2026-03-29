<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Newsletter;
use App\Mail\WelcomeNewsletter;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomNewsletter;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function store(Request $request) {
        error_log("--- NEWSLETTER START: " . $request->email . " ---");

        $validated = $request->validate([
            'email' => 'required|email|unique:newsletters,email'
        ], [
            'email.unique' => 'This address is already subscribed!'
        ]);

        $subscriber = Newsletter::create($validated);

        try {
            error_log("Attempting to send mail to: " . $subscriber->email);
        Mail::to($subscriber->email)->send(new WelcomeNewsletter());
        error_log("MAIL SENT SUCCESSFULLY (TO LOG)");
        } catch (\Exception $e) {
            error_log("!!! MAIL ERROR: " . $e->getMessage());
            Log::error("Ошибка почты: " . $e->getMessage());
            return back()->with('message', 'Ошибка при отправке: ' . $e->getMessage());
        }

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
                    ->send(new CustomNewsletter($request->subject, $request->message));
            }
        });

        return back()->with('success', "The mailing list has been launched! Emails in the queue: {$count}");
    }
}
