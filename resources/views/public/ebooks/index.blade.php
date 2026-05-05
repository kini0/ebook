@extends('layouts.app')
@section('title', 'Catalogue')

@section('content')
<section class="container-x py-10">
    <h1 class="font-display text-3xl mb-2">Catalogue</h1>
    <p class="text-gray-600 mb-6">{{ $ebooks->total() }} ebook(s) disponibles.</p>

    <form method="GET" class="card p-4 mb-6 flex flex-col md:flex-row gap-3 md:items-end">
        <div class="flex-1">
            <label class="label">Rechercher</label>
            <input name="q" value="{{ $filters['q'] ?? '' }}" class="input" placeholder="Titre, auteur, mot-clé...">
        </div>
        <div>
            <label class="label">Catégorie</label>
            <select name="category" class="input">
                <option value="">Toutes</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->slug }}" @selected(($filters['category'] ?? null) === $cat->slug)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Tri</label>
            <select name="sort" class="input">
                <option value="published_at" @selected(($filters['sort'] ?? null) === 'published_at')>Plus récents</option>
                <option value="price_cents"  @selected(($filters['sort'] ?? null) === 'price_cents')>Prix</option>
                <option value="download_count" @selected(($filters['sort'] ?? null) === 'download_count')>Popularité</option>
            </select>
        </div>
        <button class="btn-primary">Filtrer</button>
    </form>

    @if ($ebooks->isEmpty())
        <div class="card p-10 text-center text-gray-500">Aucun ebook ne correspond à votre recherche.</div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach ($ebooks as $ebook)
                <x-ebook-card :ebook="$ebook" />
            @endforeach
        </div>
        <div class="mt-8">{{ $ebooks->links() }}</div>
    @endif
</section>
@endsection
