<meta charset="utf-8" />
<meta name="viewport"
    content="width=device-width, initial-scale=1.0{{ $scalable ?? false ? ', maximum-scale=1.0, user-scalable=0' : '' }}" />

@if ($transitions ?? false)
    <meta name="view-transition" content="same-origin">
@endif

<title>{{ $title ?? config('app.name') }}</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<link href="{{ tailwindcss('css/app.css') }}" rel="stylesheet" />

<x-importmap::tags />
