@extends('layouts.admin')
@section('title', 'Ebooks')
@section('page_title', 'Gestion des ebooks')

@section('content')
<div class="flex items-center justify-between mb-4">
    <form method="GET" class="flex gap-2">
        <input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Rechercher..." class="input">
        <button class="btn-outline">Filtrer</button>
    </form>
    <a href="{{ route('admin.ebooks.create') }}" class="btn-gold">+ Ajouter un ebook</a>
</div>

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left">
            <tr>
                <th class="px-4 py-3">Titre</th>
                <th class="px-4 py-3">Catégorie</th>
                <th class="px-4 py-3">Prix</th>
                <th class="px-4 py-3">Téléch.</th>
                <th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($ebooks as $e)
                <tr>
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $e->title }}</div>
                        <div class="text-xs text-gray-500">{{ $e->author }}</div>
                    </td>
                    <td class="px-4 py-3">{{ $e->category?->name ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $e->formatted_price }}</td>
                    <td class="px-4 py-3">{{ $e->download_count }}</td>
                    <td class="px-4 py-3">
                        @if ($e->is_published)
                            <span class="badge-success">Publié</span>
                        @else
                            <span class="badge-warning">Brouillon</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.ebooks.edit', $e) }}" class="text-brand-700 hover:underline">Modifier</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Aucun ebook.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $ebooks->links() }}</div>
@endsection
