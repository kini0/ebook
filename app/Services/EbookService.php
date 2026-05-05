<?php

namespace App\Services;

use App\Models\Ebook;
use App\Repositories\Contracts\EbookRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EbookService
{
    public function __construct(protected EbookRepositoryInterface $ebooks) {}

    public function create(array $data, ?UploadedFile $cover = null, ?UploadedFile $file = null): Ebook
    {
        $data['slug'] ??= Str::slug($data['title']) . '-' . Str::random(5);

        if ($cover) {
            $data['cover_path'] = $this->storeCover($cover);
        }
        if ($file) {
            $data['file_path']       = $this->storeFile($file);
            $data['file_format']     = $file->getClientOriginalExtension() ?: 'pdf';
            $data['file_size_bytes'] = $file->getSize();
        }

        if (! empty($data['is_published']) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return Ebook::create($data);
    }

    public function update(Ebook $ebook, array $data, ?UploadedFile $cover = null, ?UploadedFile $file = null): Ebook
    {
        if ($cover) {
            $this->deleteIfExists($ebook->cover_path, 'public');
            $data['cover_path'] = $this->storeCover($cover);
        }
        if ($file) {
            $this->deleteIfExists($ebook->file_path, 'local');
            $data['file_path']       = $this->storeFile($file);
            $data['file_format']     = $file->getClientOriginalExtension() ?: $ebook->file_format;
            $data['file_size_bytes'] = $file->getSize();
        }
        if (! empty($data['is_published']) && empty($ebook->published_at)) {
            $data['published_at'] = now();
        }
        $ebook->fill($data)->save();
        return $ebook->refresh();
    }

    public function delete(Ebook $ebook): void
    {
        $ebook->delete(); // soft delete; file kept on disk for traceability
    }

    public function incrementViews(Ebook $ebook): void
    {
        $ebook->newQuery()->whereKey($ebook->id)->increment('view_count');
    }

    public function incrementDownloads(Ebook $ebook): void
    {
        $ebook->newQuery()->whereKey($ebook->id)->increment('download_count');
    }

    private function storeCover(UploadedFile $file): string
    {
        return $file->store('ebooks/covers', 'public');
    }

    private function storeFile(UploadedFile $file): string
    {
        // Stored on the LOCAL (private) disk — never publicly accessible.
        return $file->store('ebooks/files', config('filesystems.default', 'local'));
    }

    private function deleteIfExists(?string $path, string $disk): void
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}
