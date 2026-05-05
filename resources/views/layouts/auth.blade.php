<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Connexion') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-brand-900 via-brand-800 to-brand-700 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="font-display text-3xl text-gold-400">{{ config('app.name') }}</a>
        </div>
        <div class="card p-8">
            @if (session('success'))
                <div class="mb-4 px-3 py-2 rounded bg-green-50 text-green-800 text-sm border border-green-200">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 px-3 py-2 rounded bg-red-50 text-red-700 text-sm border border-red-200">
                    @foreach ($errors->all() as $err) <div>{{ $err }}</div> @endforeach
                </div>
            @endif
            @yield('content')
        </div>
        <div class="text-center text-brand-200 text-xs mt-4">
            © {{ now()->year }} {{ config('app.name') }}
        </div>
    </div>
</body>
</html>
