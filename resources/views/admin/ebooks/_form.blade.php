@php $isEdit = isset($ebook); @endphp

<div class="grid lg:grid-cols-[1fr_320px] gap-6">
    <div class="card p-6 space-y-4">
        <div>
            <label class="label">Titre *</label>
            <input name="title" value="{{ old('title', $ebook->title ?? '') }}" class="input" required>
        </div>
        <div>
            <label class="label">Sous-titre</label>
            <input name="subtitle" value="{{ old('subtitle', $ebook->subtitle ?? '') }}" class="input">
        </div>
        <div class="grid md:grid-cols-2 gap-3">
            <div>
                <label class="label">Auteur *</label>
                <input name="author" value="{{ old('author', $ebook->author ?? '') }}" class="input" required>
            </div>
            <div>
                <label class="label">Catégorie</label>
                <select name="category_id" class="input">
                    <option value="">—</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $ebook->category_id ?? null) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="label">Description courte</label>
            <textarea name="short_description" rows="2" class="input">{{ old('short_description', $ebook->short_description ?? '') }}</textarea>
        </div>
        <div>
            <label class="label">Description complète *</label>
            <textarea name="description" rows="8" class="input" required>{{ old('description', $ebook->description ?? '') }}</textarea>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div><label class="label">ISBN</label><input name="isbn" value="{{ old('isbn', $ebook->isbn ?? '') }}" class="input"></div>
            <div><label class="label">Langue</label><input name="language" value="{{ old('language', $ebook->language ?? 'fr') }}" class="input"></div>
            <div><label class="label">Pages</label><input type="number" name="pages" value="{{ old('pages', $ebook->pages ?? '') }}" class="input"></div>
        </div>
    </div>

    <aside class="space-y-4">
        <div class="card p-5">
            <h4 class="font-display text-base mb-3">Tarification (FCFA)</h4>
            <div><label class="label">Prix *</label><input type="number" name="price_cents" value="{{ old('price_cents', $ebook->price_cents ?? '') }}" class="input" required></div>
            <div class="mt-3"><label class="label">Prix barré (optionnel)</label><input type="number" name="compare_at_cents" value="{{ old('compare_at_cents', $ebook->compare_at_cents ?? '') }}" class="input"></div>
        </div>
        <div class="card p-5">
            <h4 class="font-display text-base mb-3">Visibilité</h4>
            <label class="flex items-center gap-2 mb-2">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $ebook->is_published ?? false))> Publié
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $ebook->is_featured ?? false))> Mis en avant
            </label>
        </div>
        <div class="card p-5">
            <h4 class="font-display text-base mb-3">Fichiers</h4>
            <div><label class="label">Couverture {!! $isEdit ? '' : '*' !!}</label><input type="file" name="cover" class="input" accept="image/*" {{ $isEdit ? '' : 'required' }}></div>
            <div class="mt-3"><label class="label">Fichier ebook (PDF/EPUB) {!! $isEdit ? '' : '*' !!}</label><input type="file" name="file" class="input" accept=".pdf,.epub,.mobi" {{ $isEdit ? '' : 'required' }}></div>
            @if ($isEdit && $ebook->cover_path)
                <img src="{{ $ebook->cover_url }}" class="mt-3 rounded border w-full" alt="" onerror="this.style.display='none'">
            @endif
        </div>
        <button class="btn-gold w-full">{{ $isEdit ? 'Mettre à jour' : 'Créer l\'ebook' }}</button>
    </aside>
</div>
