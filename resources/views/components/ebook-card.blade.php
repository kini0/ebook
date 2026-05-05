@props(['ebook'])
<a href="{{ route('ebooks.show', $ebook) }}" class="group card overflow-hidden hover:-translate-y-1 transition-transform">
    <div class="aspect-[3/4] bg-gray-100 relative overflow-hidden">
        <img src="{{ $ebook->cover_url }}" alt="{{ $ebook->title }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform"
             onerror="this.style.display='none'">
        @if ($ebook->discount_percent)
            <span class="absolute top-2 left-2 badge-gold">−{{ $ebook->discount_percent }}%</span>
        @endif
        @if ($ebook->is_featured)
            <span class="absolute top-2 right-2 badge-gold">Best</span>
        @endif
    </div>
    <div class="p-4">
        <div class="text-xs uppercase tracking-wider text-gray-500">{{ $ebook->category?->name }}</div>
        <h3 class="font-display text-lg leading-tight mt-1 line-clamp-2">{{ $ebook->title }}</h3>
        <div class="text-sm text-gray-600 mt-1">par {{ $ebook->author }}</div>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-brand-800 font-bold text-lg">{{ $ebook->formatted_price }}</span>
            @if ($ebook->formatted_compare_at)
                <span class="text-gray-400 line-through text-sm">{{ $ebook->formatted_compare_at }}</span>
            @endif
        </div>
    </div>
</a>
