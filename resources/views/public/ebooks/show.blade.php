@extends('layouts.app')
@section('title', $ebook->title)

@section('content')
<section class="container-x py-10">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-brand-700">Accueil</a> /
        <a href="{{ route('ebooks.index') }}" class="hover:text-brand-700">Catalogue</a> /
        <span class="text-brand-900">{{ $ebook->title }}</span>
    </nav>

    <div class="grid md:grid-cols-[320px_1fr] gap-10">
        <div class="card overflow-hidden">
            <img src="{{ $ebook->cover_url }}" alt="{{ $ebook->title }}" class="w-full aspect-[3/4] object-cover" onerror="this.style.display='none'">
        </div>

        <div>
            <div class="text-xs uppercase tracking-wider text-gold-600 font-semibold">{{ $ebook->category?->name }}</div>
            <h1 class="font-display text-3xl md:text-4xl mt-2 leading-tight">{{ $ebook->title }}</h1>
            @if ($ebook->subtitle) <p class="text-gray-600 mt-1">{{ $ebook->subtitle }}</p> @endif
            <p class="text-gray-700 mt-3">par <span class="font-semibold">{{ $ebook->author }}</span></p>

            <div class="mt-6 flex items-baseline gap-3">
                <span class="text-3xl font-bold text-brand-800">{{ $ebook->formatted_price }}</span>
                @if ($ebook->formatted_compare_at)
                    <span class="text-gray-400 line-through">{{ $ebook->formatted_compare_at }}</span>
                    <span class="badge-gold">Économisez {{ $ebook->discount_percent }} %</span>
                @endif
            </div>

            <form method="POST" action="{{ route('cart.add', $ebook) }}" class="mt-6 flex gap-3">
                @csrf
                <button type="submit" class="btn-gold px-7 py-3">Ajouter au panier</button>
                <a href="{{ route('cart.index') }}" class="btn-outline px-7 py-3">Voir le panier</a>
            </form>

            <div class="mt-8 grid grid-cols-2 gap-3 text-sm">
                <div class="card p-3"><span class="text-gray-500">Pages :</span> <span class="font-semibold">{{ $ebook->pages ?? '—' }}</span></div>
                <div class="card p-3"><span class="text-gray-500">Format :</span> <span class="font-semibold">{{ strtoupper($ebook->file_format) }}</span></div>
                <div class="card p-3"><span class="text-gray-500">Langue :</span> <span class="font-semibold">{{ strtoupper($ebook->language) }}</span></div>
                <div class="card p-3"><span class="text-gray-500">ISBN :</span> <span class="font-semibold">{{ $ebook->isbn ?? '—' }}</span></div>
            </div>

            <div class="prose max-w-none mt-8">
                <h3 class="font-display">Description</h3>
                <p class="whitespace-pre-line text-gray-700">{{ $ebook->description }}</p>
            </div>
        </div>
    </div>

    @if ($related->isNotEmpty())
        <section class="mt-16">
            <h2 class="font-display text-2xl mb-5">Vous aimerez aussi</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                @foreach ($related as $r) <x-ebook-card :ebook="$r" /> @endforeach
            </div>
        </section>
    @endif
</section>
@endsection
