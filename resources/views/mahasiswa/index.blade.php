@extends('layouts.app')

@section('title', 'Mahasiswa')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Pilih Fakultas</h1>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <a href="{{ route('fakultasfst.sains-teknologi') }}">
                    <img src="images/inf.jpeg" class="card-img-top" alt="Fakultas Sains dan Teknologi">
                    <div class="card-body text-center">
                        <p class="card-text">Fakultas Sains dan Teknologi</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">

                <img src="images/bisnis.jpeg" class="card-img-top" alt="Fakultas Ekonomi dan Bisnis">
                <div class="card-body text-center">
                    <p class="card-text">Fakultas Ekonomi dan Bisnis</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="images/vokasi.jpeg" class="card-img-top" alt="Vokasi">
                <div class="card-body text-center">
                    <p class="card-text">Vokasi</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin logout?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Ya, Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    body {
        background-color: #def4ff;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card img {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .card-body {
        background-color: rgba(161, 161, 161, 0.19); /* Warna kartu dengan transparansi 19% */
        padding: 20px;
    }

    .card-text {
        color: #343a40;
        font-size: 1.2rem;
        margin: 0;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
@endsection
