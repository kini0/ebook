<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ebook;
use App\Repositories\Contracts\EbookRepositoryInterface;
use App\Services\EbookService;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    public function __construct(
        protected EbookRepositoryInterface $ebooks,
        protected EbookService $service,
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['category', 'q', 'min', 'max', 'sort', 'order']);

        return view('public.ebooks.index', [
            'ebooks'     => $this->ebooks->paginatePublic($filters),
            'categories' => Category::active()->orderBy('position')->get(),
            'filters'    => $filters,
        ]);
    }

    public function show(Ebook $ebook)
    {
        abort_unless($ebook->is_published, 404);
        $this->service->incrementViews($ebook);

        return view('public.ebooks.show', [
            'ebook'   => $ebook->load('category'),
            'related' => Ebook::published()
                ->where('id', '!=', $ebook->id)
                ->where('category_id', $ebook->category_id)
                ->limit(4)->get(),
        ]);
    }
}
