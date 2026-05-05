@extends('layouts.admin')
@section('title', 'Tableau de bord')
@section('page_title', 'Tableau de bord')

@section('content')

@php
    $cards = [
        ['CA aujourd\'hui',    $summary['revenue_today'], 'gold-500'],
        ['CA · 7 jours',       $summary['revenue_7d'],    'brand-700'],
        ['CA · 30 jours',      $summary['revenue_30d'],   'brand-800'],
        ['Commandes (30 j)',   $summary['orders_30d'],    'green-600', 'count'],
        ['Clients',            $summary['customers'],     'brand-900', 'count'],
        ['Ebooks publiés',     $summary['ebooks'],        'gold-600',  'count'],
    ];
@endphp

<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    @foreach ($cards as $i => $c)
        <div class="card p-5">
            <div class="text-sm text-gray-500">{{ $c[0] }}</div>
            <div class="font-display text-3xl mt-1 text-{{ $c[2] }}">
                @if (($c[3] ?? null) === 'count')
                    {{ number_format($c[1], 0, ',', ' ') }}
                @else
                    {{ number_format($c[1], 0, ',', ' ') }} <span class="text-base">FCFA</span>
                @endif
            </div>
        </div>
    @endforeach
</div>

<div class="card p-5 mb-8">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-display text-lg">Revenu sur 30 jours</h3>
        <a href="{{ route('admin.export.orders') }}" class="btn-outline text-sm">Export CSV</a>
    </div>
    <canvas id="revenue-chart" height="80"></canvas>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="card p-5">
        <h3 class="font-display text-lg mb-3">Commandes récentes</h3>
        <table class="w-full text-sm">
            <thead class="text-left text-gray-500 border-b border-gray-100">
                <tr><th class="py-2">Référence</th><th>Client</th><th>Total</th><th>Statut</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($recentOrders as $o)
                    <tr>
                        <td class="py-2"><a href="{{ route('admin.orders.show', $o) }}" class="text-brand-700">{{ $o->reference }}</a></td>
                        <td>{{ $o->billing_name }}</td>
                        <td>{{ $o->formatted_total }}</td>
                        <td><span class="{{ $o->status->badgeClass() }}">{{ $o->status->label() }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card p-5">
        <h3 class="font-display text-lg mb-3">Top 5 ebooks</h3>
        <ul class="divide-y divide-gray-100 text-sm">
            @foreach ($bestsellers as $e)
                <li class="py-2 flex justify-between">
                    <span class="font-medium">{{ $e->title }}</span>
                    <span class="text-gray-500">{{ $e->download_count }} téléch.</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
    const labels = @json(array_keys($revenueChart));
    const data   = @json(array_values($revenueChart));
    new Chart(document.getElementById('revenue-chart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'CA (FCFA)',
                data,
                borderColor: '#C9A24C',
                backgroundColor: 'rgba(201,162,76,0.15)',
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endpush
@endsection
