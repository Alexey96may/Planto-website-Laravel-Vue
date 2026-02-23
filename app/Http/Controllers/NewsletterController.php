<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Newsletter;
use App\Mail\WelcomeNewsletter;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomNewsletter;

class NewsletterController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletters,email'
        ], [
            'email.unique' => 'Этот адрес уже подписан!'
        ]);

        $subscriber = Newsletter::create($validated);
        
        Mail::to($subscriber->email)->send(new WelcomeNewsletter());

        return back()->with('message', 'Письмо отправлено!');
    }

    
    public function sendBroadcast(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $subscribers = Newsletter::all();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)
                ->queue(new CustomNewsletter($request->subject, $request->message));
        }

        return back()->with('success', 'Рассылка запущена! Писем в очереди: ' . $subscribers->count());
    }
}
