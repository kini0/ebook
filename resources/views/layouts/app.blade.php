<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta_description', 'EbookSaaS — La bibliothèque numérique premium. Achetez et téléchargez vos ebooks instantanément.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">

    @include('partials.navbar')

    @if (session('success'))
        <div class="container-x mt-4">
            <div class="card border-green-200 bg-green-50 text-green-800 px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if (session('error') || $errors->any())
        <div class="container-x mt-4">
            <div class="card border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm">
                {{ session('error') }}
                @if ($errors->any())
                    <ul class="list-disc pl-5 mt-1">
                        @foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')

</body>
</html>
