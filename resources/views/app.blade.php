<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Clerky - Smart clinical clerking powered by AI. Specialty-specific templates, structured SOAP summaries, and adaptive questions for every presenting complaint. Built by a medical student, free and open-source.">
    <meta name="keywords"
        content="clinical clerking, medical, SOAP, AI, healthcare, medical student, open-source, clerky">
    <meta name="author" content="Clerky">
    <meta name="robots" content="index, follow">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <meta name="theme-color" content="#0a0a0a">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:title" content="{{ config('app.name', 'Clerky') }} - Smart Clinical Clerking">
    <meta property="og:description"
        content="Smart clinical clerking powered by AI. Specialty-specific templates, structured SOAP summaries, and adaptive questions for every presenting complaint. Free and open-source.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og-image.png') }}">
    <meta property="og:image:width" content="1096">
    <meta property="og:image:height" content="582">
    <meta property="og:image:alt" content="Clerky - Smart Clinical Clerking">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name', 'Clerky') }}">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ config('app.name', 'Clerky') }} - Smart Clinical Clerking">
    <meta name="twitter:description"
        content="Smart clinical clerking powered by AI. Specialty-specific templates, structured SOAP summaries, and adaptive questions for every presenting complaint. Free and open-source.">
    <meta name="twitter:image" content="{{ asset('images/og-image.png') }}">
    <meta name="twitter:image:alt" content="Clerky - Smart Clinical Clerking">

    @vite(['resources/css/app.css', 'resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
    <x-inertia::head>
        <title>{{ config('app.name', 'Laravel') }}</title>
    </x-inertia::head>
</head>

<body class="font-sans antialiased">
    <x-inertia::app />
</body>

</html>