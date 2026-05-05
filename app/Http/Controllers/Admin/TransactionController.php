<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionRepositoryInterface $transactions,
        protected PaymentService $paymentService,
    ) {}

    public function index(Request $request)
    {
        return view('admin.transactions.index', [
            'transactions' => $this->transactions->paginateAdmin($request->only('status', 'gateway'), 20),
            'filters'      => $request->only('status', 'gateway'),
            'statuses'     => PaymentStatus::cases(),
        ]);
    }

    public function show(Transaction $transaction)
    {
        return view('admin.transactions.show', [
            'transaction' => $transaction->load(['order.items.ebook', 'user']),
        ]);
    }

    public function reconcile(Transaction $transaction)
    {
        $this->paymentService->reconcile($transaction);
        return back()->with('success', 'Statut du paiement actualisé depuis le fournisseur.');
    }
}
