@extends('layouts.app')

@section('title', 'Ubah Produk · Qashya POS')

@section('content')
<h1 class="mb-6 text-2xl font-semibold">Ubah produk</h1>
@include('products._form', ['product' => $product])
@endsection