<?php

namespace App\Services;

use App\Exceptions\DownloadException;
use App\Models\Ebook;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class DownloadService
{
    public function __construct(protected EbookService $ebookService) {}

    /**
     * Generate a temporary signed URL the user can use to download the ebook
     * file. The URL expires after DOWNLOAD_URL_TTL seconds.
     */
    public function generateSignedUrl(User $user, Order $order, Ebook $ebook): string
    {
        $this->authorize($user, $order, $ebook);

        $ttl = (int) config('payment.downloads.url_ttl_seconds', 900);

        return URL::temporarySignedRoute(
            'download.serve',
            now()->addSeconds($ttl),
            [
                'order' => $order->reference,
                'ebook' => $ebook->slug,
            ]
        );
    }

    /**
     * Validate that the user is allowed to download.
     *
     * @throws DownloadException
     */
    public function authorize(User $user, Order $order, Ebook $ebook): void
    {
        if ($order->user_id !== $user->id) {
            throw new DownloadException('Cette commande ne vous appartient pas.');
        }
        if (! $order->isPaid()) {
            throw new DownloadException('La commande n\'a pas été payée.');
        }
        if (! $user->hasVerifiedEmail()) {
            throw new DownloadException('Veuillez vérifier votre adresse e-mail avant de télécharger.');
        }
        if (! $order->items()->where('ebook_id', $ebook->id)->exists()) {
            throw new DownloadException('Cet ebook ne fait pas partie de la commande.');
        }

        $max = (int) config('payment.downloads.max_per_order', 10);
        $count = $order->downloads()->where('ebook_id', $ebook->id)->count();
        if ($count >= $max) {
            throw new DownloadException("Limite de {$max} téléchargements atteinte pour cet ebook.");
        }
    }

    public function recordDownload(User $user, Order $order, Ebook $ebook, Request $request): void
    {
        $order->downloads()->create([
            'user_id'      => $user->id,
            'ebook_id'     => $ebook->id,
            'ip_address'   => $request->ip(),
            'user_agent'   => substr((string) $request->userAgent(), 0, 500),
            'downloaded_at'=> now(),
        ]);

        $this->ebookService->incrementDownloads($ebook);
    }
}
