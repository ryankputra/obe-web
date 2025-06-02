@extends('layouts.app')

@section('title', 'Bobot Nilai Mata Kuliah')

@section('content')
    <div class="container-fluid">
        <h1 class="dashboard-heading mt-4">Bobot Nilai Mata Kuliah</h1>

        <div class="row mb-3">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-filter me-2"></i>Filter Mata Kuliah
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('bobot_nilai.index') }}" class="row g-3">
                            <div class="col-md-9">
                                <label for="search" class="form-label">Cari Mata Kuliah (Kode/Nama)</label>
                                <input type="text" class="form-control" id="search" name="search"
                                    placeholder="Masukkan Kode atau Nama Mata Kuliah" value="{{ $search ?? '' }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                                <a href="{{ route('bobot_nilai.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-sync-alt me-1"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered text-center" id="bobotNilaiTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Jumlah Mahasiswa</th>
                                <th>Aksi</th> {{-- Kolom aksi jika diperlukan untuk mengatur bobot --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mataKuliahs as $index => $mk)
                                <tr>
                                    <td>{{ $mataKuliahs->firstItem() + $index }}</td>
                                    <td>{{ $mk->kode_mk }}</td>
                                    <td class="text-start">{{ $mk->nama_mk }}</td>
                                    <td>{{ $mk->mahasiswas_count }}</td> {{-- Menggunakan hasil dari withCount --}}
                                    <td>
                                        {{-- Tombol untuk mengatur bobot nilai per mata kuliah bisa ditambahkan di sini --}}
                                        {{-- Contoh: --}}
                                        {{-- <a href="{{ route('bobot_nilai.edit', $mk->id) }}" class="btn btn-outline-success btn-sm">
                                            Atur Bobot
                                        </a> --}}
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="aturBobot('{{ $mk->id }}', '{{ $mk->nama_mk }}')">
                                            Atur Bobot
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data mata kuliah yang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($mataKuliahs->hasPages())
                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button class="btn btn-primary me-2"
                            onclick="navigateToPage('{{ $mataKuliahs->appends(request()->query())->previousPageUrl() }}')"
                            {{ $mataKuliahs->onFirstPage() ? 'disabled' : '' }}>
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </button>
                        <button class="btn btn-primary"
                            onclick="navigateToPage('{{ $mataKuliahs->appends(request()->query())->nextPageUrl() }}')"
                            {{ $mataKuliahs->hasMorePages() ? '' : 'disabled' }}>
                            Selanjutnya <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal untuk Atur Bobot (Contoh) --}}
    <div class="modal fade" id="aturBobotModal" tabindex="-1" aria-labelledby="aturBobotModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="aturBobotModalLabel">Atur Bobot Nilai untuk: <span id="modalNamaMK"></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Formulir untuk mengatur bobot nilai (misalnya: Tugas, UTS, UAS, dll.) akan ditampilkan di sini.</p>
            <p>ID Mata Kuliah: <span id="modalIdMK"></span></p>
            {{-- Contoh Form --}}
            {{-- <form id="formAturBobot">
                <input type="hidden" id="inputMataKuliahId" name="mata_kuliah_id">
                <div class="mb-3">
                    <label for="bobot_tugas" class="form-label">Bobot Tugas (%)</label>
                    <input type="number" class="form-control" id="bobot_tugas" name="bobot_tugas" min="0" max="100">
                </div>
                <div class="mb-3">
                    <label for="bobot_uts" class="form-label">Bobot UTS (%)</label>
                    <input type="number" class="form-control" id="bobot_uts" name="bobot_uts" min="0" max="100">
                </div>
                <div class="mb-3">
                    <label for="bobot_uas" class="form-label">Bobot UAS (%)</label>
                    <input type="number" class="form-control" id="bobot_uas" name="bobot_uas" min="0" max="100">
                </div>
                <div class="alert alert-info">Pastikan total bobot adalah 100%.</div>
            </form> --}}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" onclick="simpanBobot()">Simpan Bobot</button>
          </div>
        </div>
      </div>
    </div>


    @if (session('success'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast align-items-center text-white bg-danger border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('styles')
    <style>
        body {
            background-color: #def4ff; /* Warna latar belakang yang lebih lembut */
        }

        .dashboard-heading {
            font-size: 2rem;
            font-weight: bold;
            color: #333; /* Warna teks yang lebih gelap untuk kontras */
        }

        .table thead th {
            background-color: rgb(0, 114, 202) !important; /* Biru primer yang konsisten */
            color: white !important;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #cceeff; /* Warna hover yang lebih jelas */
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #b8daff; /* Border yang lebih halus */
            vertical-align: middle;
        }

        .table th {
            font-size: 1rem; /* Ukuran font standar */
        }

        .table td {
            font-size: 0.9rem; /* Ukuran font sedikit lebih kecil untuk data */
        }

        .text-start {
            text-align: left !important;
        }

        .btn-outline-primary {
            border-color: #007bff;
            color: #007bff;
        }
        .btn-outline-primary:hover {
            background-color: #007bff;
            color: white;
        }

        .btn-primary {
            background-color: rgb(0, 114, 202) !important;
            border-color: rgb(0, 114, 202) !important;
        }

        .btn-primary:hover {
            background-color: rgb(0, 94, 182) !important; /* Warna hover yang sedikit lebih gelap */
            border-color: rgb(0, 94, 182) !important;
        }

        .btn-secondary {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
        }

        .btn-secondary:hover {
            background-color: #5a6268 !important;
            border-color: #5a6268 !important;
        }

        .card {
            border-radius: 0.5rem; /* Radius border yang lebih halus */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Shadow halus */
        }

        .card-header {
            border-radius: 0.5rem 0.5rem 0 0 !important;
            font-weight: bold;
        }

        .form-control {
            border-radius: 0.25rem; /* Radius border standar */
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            border-color: rgb(0, 114, 202);
            box-shadow: 0 0 0 0.2rem rgba(0, 114, 202, 0.25); /* Shadow fokus yang lebih standar */
        }

        .form-label {
            font-weight: 500;
            color: #333;
        }
    </style>
@endsection

@section('scripts')
    <script>
        function navigateToPage(url) {
            if (url) {
                window.location.href = url;
            }
        }

        // Fungsi untuk membuka modal dan mengisi data
        function aturBobot(mataKuliahId, namaMk) {
            document.getElementById('modalIdMK').textContent = mataKuliahId;
            document.getElementById('modalNamaMK').textContent = namaMk;
            // Jika Anda memiliki form di dalam modal:
            // document.getElementById('inputMataKuliahId').value = mataKuliahId;
            
            // Di sini Anda bisa melakukan fetch data bobot yang sudah ada untuk mata kuliah ini jika perlu
            // dan mengisi form di dalam modal.

            var aturBobotModal = new bootstrap.Modal(document.getElementById('aturBobotModal'));
            aturBobotModal.show();
        }

        function simpanBobot() {
            // Logika untuk mengambil data dari form di dalam modal
            // dan mengirimkannya ke server (misalnya via AJAX)
            // const mataKuliahId = document.getElementById('inputMataKuliahId').value;
            // const bobotTugas = document.getElementById('bobot_tugas').value;
            // const bobotUts = document.getElementById('bobot_uts').value;
            // const bobotUas = document.getElementById('bobot_uas').value;

            // console.log("Simpan Bobot untuk MK ID:", mataKuliahId);
            // console.log("Tugas:", bobotTugas, "UTS:", bobotUts, "UAS:", bobotUas);

            // Tutup modal setelah (misalnya) berhasil disimpan
            // var aturBobotModal = bootstrap.Modal.getInstance(document.getElementById('aturBobotModal'));
            // aturBobotModal.hide();

            alert("Fungsi simpanBobot() dipanggil. Implementasikan logika penyimpanan di sini.");
        }

        // Auto-dismiss toasts if any
        document.addEventListener('DOMContentLoaded', function () {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));
            var toastList = toastElList.map(function (toastEl) {
                var toast = new bootstrap.Toast(toastEl, { delay: 5000 });
                toast.show();
                return toast;
            });
        });
    </script>
@endsection
