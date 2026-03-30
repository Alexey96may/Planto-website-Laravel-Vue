<x-mail::message>
# {{ $subject }}

{{ $body }}

<x-mail::button :url="config('app.url')">
Go to the website
</x-mail::button>

Thank you,<br>
Team {{ config('app.name') }}
</x-mail::message>
