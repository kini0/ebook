<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EbookResource;
use App\Models\Ebook;
use App\Repositories\Contracts\EbookRepositoryInterface;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    public function __construct(protected EbookRepositoryInterface $ebooks) {}

    public function index(Request $request)
    {
        return EbookResource::collection(
            $this->ebooks->paginatePublic($request->only('category', 'q', 'min', 'max', 'sort', 'order'))
        );
    }

    public function show(Ebook $ebook): EbookResource
    {
        abort_unless($ebook->is_published, 404);
        return new EbookResource($ebook->load('category'));
    }
}
