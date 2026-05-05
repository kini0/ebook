<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\Contracts\EbookRepositoryInterface;

class HomeController extends Controller
{
    public function __invoke(EbookRepositoryInterface $ebooks)
    {
        return view('public.home', [
            'featured'    => $ebooks->featured(6),
            'bestsellers' => $ebooks->bestsellers(6),
            'categories'  => Category::active()->orderBy('position')->get(),
        ]);
    }
}
