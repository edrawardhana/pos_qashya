@extends('layouts.app')

@section('title', 'Ubah Pengguna · Qashya POS')

@section('content')
<h1 class="mb-6 text-2xl font-semibold">Ubah pengguna</h1>
@include('users._form', ['user' => $user])
@endsection