<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function index()
    {
        return view('public.cart.index', [
            'items'    => $this->cart->items(),
            'subtotal' => $this->cart->formattedSubtotal(),
        ]);
    }

    public function add(Request $request, Ebook $ebook)
    {
        abort_unless($ebook->is_published, 404);
        $this->cart->add($ebook);
        return back()->with('success', "« {$ebook->title} » a été ajouté au panier.");
    }

    public function remove(int $ebookId)
    {
        $this->cart->remove($ebookId);
        return back()->with('success', 'Article retiré du panier.');
    }

    public function clear()
    {
        $this->cart->clear();
        return back()->with('success', 'Panier vidé.');
    }
}
