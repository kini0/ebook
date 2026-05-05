<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function orders(): StreamedResponse
    {
        $columns = ['Référence', 'Client', 'Email', 'Méthode', 'Statut', 'Total (FCFA)', 'Payée le', 'Créée le'];

        return response()->streamDownload(function () use ($columns) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM (Excel-friendly)
            fputcsv($out, $columns, ';');

            Order::with('user')->orderBy('id')->chunk(500, function ($chunk) use ($out) {
                foreach ($chunk as $order) {
                    fputcsv($out, [
                        $order->reference,
                        $order->billing_name,
                        $order->billing_email,
                        $order->payment_method,
                        $order->status?->value,
                        $order->total_cents,
                        optional($order->paid_at)->format('Y-m-d H:i'),
                        $order->created_at->format('Y-m-d H:i'),
                    ], ';');
                }
            });
            fclose($out);
        }, 'commandes-' . now()->format('Ymd-His') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function transactions(): StreamedResponse
    {
        $columns = ['Référence', 'Commande', 'Passerelle', 'Fournisseur', 'Montant', 'Statut', 'Traitée le'];

        return response()->streamDownload(function () use ($columns) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $columns, ';');

            Transaction::with('order')->orderBy('id')->chunk(500, function ($chunk) use ($out) {
                foreach ($chunk as $tx) {
                    fputcsv($out, [
                        $tx->reference,
                        $tx->order?->reference,
                        $tx->gateway,
                        $tx->provider,
                        $tx->amount_cents,
                        $tx->status?->value,
                        optional($tx->processed_at)->format('Y-m-d H:i'),
                    ], ';');
                }
            });
            fclose($out);
        }, 'transactions-' . now()->format('Ymd-His') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
