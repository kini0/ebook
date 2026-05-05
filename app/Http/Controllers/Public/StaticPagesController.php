<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;

class StaticPagesController extends Controller
{
    public function about()  { return view('public.about'); }
    public function faq()    { return view('public.faq'); }
    public function contact(){ return view('public.contact'); }

    public function sendContact(ContactRequest $request)
    {
        ContactMessage::create([
            ...$request->validated(),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Votre message a bien été envoyé. Nous vous répondrons sous 24 heures.');
    }
}
