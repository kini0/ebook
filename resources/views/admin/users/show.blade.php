@extends('layouts.admin')
@section('title', $user->full_name)
@section('page_title', $user->full_name)

@section('content')
<div class="grid lg:grid-cols-[1fr_320px] gap-6">
    <div class="card p-6">
        <h3 class="font-display text-lg mb-3">Commandes ({{ $user->orders->count() }})</h3>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr><th class="px-3 py-2">Référence</th><th>Total</th><th>Statut</th><th>Date</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($user->orders as $o)
                    <tr>
                        <td class="px-3 py-2"><a href="{{ route('admin.orders.show', $o) }}" class="text-brand-700">{{ $o->reference }}</a></td>
                        <td>{{ $o->formatted_total }}</td>
                        <td>{{ $o->status?->label() }}</td>
                        <td>{{ $o->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <aside class="space-y-4">
        <div class="card p-5">
            <h4 class="font-display text-base mb-2">Identité</h4>
            <div class="text-sm space-y-1">
                <div>{{ $user->email }}</div>
                <div>{{ $user->phone }}</div>
                <div>{{ $user->address }}, {{ $user->city }}</div>
                <div>{{ $user->country }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
            @csrf
            <button class="btn-outline w-full">{{ $user->is_active ? 'Désactiver le compte' : 'Réactiver le compte' }}</button>
        </form>
    </aside>
</div>
@endsection
