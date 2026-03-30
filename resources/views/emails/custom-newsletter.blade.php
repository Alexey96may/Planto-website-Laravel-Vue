<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; border: 1px solid #eee; padding: 20px; border-radius: 10px;">
        <h2 style="color: #16a34a;">{{ $subject }}</h2>
        
        <div style="margin-top: 20px; white-space: pre-wrap;">
            {{ $messageText }}
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ config('app.url') }}" 
               style="background: #16a34a; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">
               Go to the website
            </a>
        </div>

        <p style="margin-top: 40px; font-size: 12px; color: #777;">
            Thank you,<br>
            Team {{ config('app.name') }}
        </p>
    </div>
</body>
</html>