<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEbookRequest;
use App\Http\Requests\Admin\UpdateEbookRequest;
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
        $this->authorize('viewAny', Ebook::class);
        return view('admin.ebooks.index', [
            'ebooks'  => $this->ebooks->paginateAdmin($request->only('q', 'published'), 20),
            'filters' => $request->only('q', 'published'),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Ebook::class);
        return view('admin.ebooks.create', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(StoreEbookRequest $request)
    {
        $this->authorize('create', Ebook::class);
        $ebook = $this->service->create(
            $request->safe()->except(['cover', 'file']),
            $request->file('cover'),
            $request->file('file'),
        );
        return redirect()->route('admin.ebooks.edit', $ebook)
            ->with('success', 'Ebook créé avec succès.');
    }

    public function edit(Ebook $ebook)
    {
        $this->authorize('update', $ebook);
        return view('admin.ebooks.edit', [
            'ebook'      => $ebook,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateEbookRequest $request, Ebook $ebook)
    {
        $this->authorize('update', $ebook);
        $this->service->update(
            $ebook,
            $request->safe()->except(['cover', 'file']),
            $request->file('cover'),
            $request->file('file'),
        );
        return back()->with('success', 'Ebook mis à jour.');
    }

    public function destroy(Ebook $ebook)
    {
        $this->authorize('delete', $ebook);
        $this->service->delete($ebook);
        return redirect()->route('admin.ebooks.index')->with('success', 'Ebook supprimé.');
    }
}
