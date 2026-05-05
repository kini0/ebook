@extends('layouts.admin')
@section('title', 'Modifier ' . $ebook->title)
@section('page_title', 'Modifier l\'ebook')

@section('content')
<form method="POST" action="{{ route('admin.ebooks.update', $ebook) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.ebooks._form')
</form>

<form method="POST" action="{{ route('admin.ebooks.destroy', $ebook) }}" class="mt-6"
      onsubmit="return confirm('Confirmer la suppression de cet ebook ?');">
    @csrf @method('DELETE')
    <button class="btn-danger">Supprimer cet ebook</button>
</form>
@endsection
