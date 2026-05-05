@extends('layouts.admin')
@section('title', 'Nouvel ebook')
@section('page_title', 'Créer un ebook')

@section('content')
<form method="POST" action="{{ route('admin.ebooks.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.ebooks._form')
</form>
@endsection
