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
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mataKuliahs as $index => $mk)
                                <tr>
                                    <td>{{ $mataKuliahs->firstItem() + $index }}</td>
                                    <td>{{ $mk->kode_mk }}</td>
                                    <td class="text-start">{{ $mk->nama_mk }}</td>
                                    <td>{{ $mk->mahasiswas_count }}</td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#aturBobotModal"
                                            data-mk-id="{{ $mk->id }}"
                                            data-mk-nama="{{ $mk->nama_mk }}">
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

    <!-- Modal untuk Atur Bobot -->
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
            <!-- Example form structure -->
            <form id="formAturBobot">
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
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" id="simpanBobotBtn">Simpan Bobot</button>
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
            background-color: #def4ff;
        }

        .dashboard-heading {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .table thead th {
            background-color: rgb(0, 114, 202) !important;
            color: white !important;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #cceeff;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #b8daff;
            vertical-align: middle;
        }

        .btn-outline-primary {
            border-color: #007bff;
            color: #007bff;
        }
        .btn-outline-primary:hover {
            background-color: #007bff;
            color: white;
        }

        /* Modal fixes */
        .modal-backdrop {
            z-index: 1040 !important;
        }
        .modal {
            z-index: 1050 !important;
        }
    </style>
@endsection

@section('scripts')
    <!-- Load Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Initialize modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Set up the Atur Bobot modal
            const aturBobotModal = document.getElementById('aturBobotModal');
            if (aturBobotModal) {
                aturBobotModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const mkId = button.getAttribute('data-mk-id');
                    const mkNama = button.getAttribute('data-mk-nama');
                    
                    // Update modal content
                    document.getElementById('modalIdMK').textContent = mkId;
                    document.getElementById('modalNamaMK').textContent = mkNama;
                    document.getElementById('inputMataKuliahId').value = mkId;
                    
                    // Here you could load existing data via AJAX if needed
                    // loadExistingBobot(mkId);
                });
            }

            // Set up the Simpan Bobot button
            const simpanBobotBtn = document.getElementById('simpanBobotBtn');
            if (simpanBobotBtn) {
                simpanBobotBtn.addEventListener('click', function() {
                    const mataKuliahId = document.getElementById('inputMataKuliahId').value;
                    const bobotTugas = document.getElementById('bobot_tugas').value;
                    const bobotUts = document.getElementById('bobot_uts').value;
                    const bobotUas = document.getElementById('bobot_uas').value;
                    
                    // Validate input
                    if (!bobotTugas || !bobotUts || !bobotUas) {
                        alert('Harap isi semua bobot nilai');
                        return;
                    }
                    
                    // Here you would typically make an AJAX call to save the data
                    console.log('Simpan data untuk MK ID:', mataKuliahId);
                    console.log('Bobot Tugas:', bobotTugas);
                    console.log('Bobot UTS:', bobotUts);
                    console.log('Bobot UAS:', bobotUas);
                    
                    // Close modal after saving
                    bootstrap.Modal.getInstance(aturBobotModal).hide();
                    
                    // Show success message (you would replace this with actual response handling)
                    alert('Bobot nilai berhasil disimpan!');
                });
            }

            // Auto-dismiss toasts
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));
            var toastList = toastElList.map(function(toastEl) {
                return new bootstrap.Toast(toastEl, { delay: 5000 }).show();
            });
        });

        function navigateToPage(url) {
            if (url) {
                window.location.href = url;
            }
        }

        // Example function to load existing data
        function loadExistingBobot(mkId) {
            // This would be an AJAX call to your backend
            console.log('Loading existing bobot for MK ID:', mkId);
            // Example:
            // fetch(`/api/bobot-nilai/${mkId}`)
            //     .then(response => response.json())
            //     .then(data => {
            //         document.getElementById('bobot_tugas').value = data.bobot_tugas;
            //         document.getElementById('bobot_uts').value = data.bobot_uts;
            //         document.getElementById('bobot_uas').value = data.bobot_uas;
            //     });
        }
    </script>
@endsection