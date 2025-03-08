@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="container">
    <h1 class="mt-4">Profil</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Nama: {{ $user->name }}</h5>
                    <p class="card-text">Email: {{ $user->email }}</p>
                    <!-- Tambahkan informasi lain yang diperlukan -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
