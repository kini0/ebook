<?php

namespace App\Http\Controllers\Customer;

use App\Exceptions\DownloadException;
use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\Order;
use App\Services\DownloadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function __construct(protected DownloadService $downloads) {}

    public function index(Request $request)
    {
        $orders = $request->user()->orders()
            ->where('status', \App\Enums\OrderStatus::PAID->value)
            ->with('items.ebook')
            ->latest()
            ->paginate(10);

        return view('customer.downloads.index', compact('orders'));
    }

    /**
     * Generate the signed URL and redirect.
     */
    public function request(Order $order, Ebook $ebook, Request $request)
    {
        $this->authorize('view', $order);
        try {
            $url = $this->downloads->generateSignedUrl($request->user(), $order, $ebook);
        } catch (DownloadException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->away($url);
    }

    /**
     * Serve the file behind a signed URL.
     */
    public function serve(Order $order, Ebook $ebook, Request $request)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Lien de téléchargement expiré ou invalide.');
        }

        $user = $request->user();
        try {
            $this->downloads->authorize($user, $order, $ebook);
        } catch (DownloadException $e) {
            abort(403, $e->getMessage());
        }

        $disk = Storage::disk(config('filesystems.default', 'local'));
        if (! $disk->exists($ebook->file_path)) {
            abort(404, 'Fichier introuvable.');
        }

        $this->downloads->recordDownload($user, $order, $ebook, $request);

        $extension = $ebook->file_format ?: 'pdf';
        $filename  = \Illuminate\Support\Str::slug($ebook->title) . '.' . $extension;

        return $disk->download($ebook->file_path, $filename);
    }
}
