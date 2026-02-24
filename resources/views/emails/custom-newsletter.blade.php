<x-mail::message>
# {{ $subject }}

{{ $body }}

<x-mail::button :url="config('app.url')">
Перейти на сайт
</x-mail::button>

Спасибо,<br>
Команда {{ config('app.name') }}
</x-mail::message>
