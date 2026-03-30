<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Newsletter;
use App\Mail\WelcomeNewsletter;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomNewsletter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletters,email'
        ], [
            'email.unique' => 'This address is already subscribed!'
        ]);

        $subscriber = Newsletter::create($validated);

        $response = Http::post('https://api.unisender.com/ru/api/sendEmail?format=json', [
            'api_key' => env('UNISENDER_API_KEY'),
            'email'    => $subscriber->email,
            'sender_name'  => env('UNISENDER_SENDER_NAME', 'Planto Shop'),
            'sender_email' => env('UNISENDER_SENDER_EMAIL'),
            'subject'      => 'Wellcome to Planto!',
            'body'         => view('emails.welcome_newsletter')->render(),
            'list_id'      => 1,
        ]);

        if ($response->failed() || isset($response->json()['error'])) {
            Log::error("Ошибка UniSender: " . $response->body());
            return back()->with('message', 'The subscription has been completed, but there was a delay with the letter.');
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

        Newsletter::chunk(100, function ($subscribers) use ($request, &$count) {
            foreach ($subscribers as $subscriber) {
                Http::post('https://api.unisender.com/ru/api/sendEmail?format=json', [
                    'api_key'      => env('UNISENDER_API_KEY'),
                    'email'         => $subscriber->email,
                    'sender_name'   => env('UNISENDER_SENDER_NAME'),
                    'sender_email'  => env('UNISENDER_SENDER_EMAIL'),
                    'subject'       => $request->subject,
                    'body'          => view('emails.custom_newsletter', [
                                            'subject' => $request->subject, 
                                            'messageText' => $request->message
                                       ])->render(),
                    'list_id'       => 1,
                ]);
                $count++;
            }
        });

        return back()->with('success', "The mailing list has been launched! Emails in the queue: {$count}");
    }
}
