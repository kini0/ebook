@extends('layouts.admin')
@section('title', 'Utilisateurs')
@section('page_title', 'Utilisateurs')

@section('content')
<form method="GET" class="flex gap-2 mb-4">
    <input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nom, e-mail..." class="input flex-1">
    <select name="role" class="input">
        <option value="">Tous rôles</option>
        <option value="admin"    @selected(($filters['role'] ?? null) === 'admin')>Admin</option>
        <option value="customer" @selected(($filters['role'] ?? null) === 'customer')>Client</option>
    </select>
    <button class="btn-outline">Filtrer</button>
</form>

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left">
            <tr>
                <th class="px-4 py-3">Nom</th>
                <th class="px-4 py-3">E-mail</th>
                <th class="px-4 py-3">Téléphone</th>
                <th class="px-4 py-3">Rôle</th>
                <th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3">Inscrit le</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($users as $u)
                <tr>
                    <td class="px-4 py-3"><a href="{{ route('admin.users.show', $u) }}" class="text-brand-700">{{ $u->full_name }}</a></td>
                    <td class="px-4 py-3">{{ $u->email }}</td>
                    <td class="px-4 py-3">{{ $u->phone }}</td>
                    <td class="px-4 py-3">{{ $u->role?->label() }}</td>
                    <td class="px-4 py-3">
                        @if ($u->is_active) <span class="badge-success">Actif</span> @else <span class="badge-danger">Désactivé</span> @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $u->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection
