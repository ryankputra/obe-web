@extends('layouts.app')

@section('title', 'Tambah Dosen Baru')

@section('content')
<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Tambah Dosen Baru</h1>

    <!-- Alert sukses -->
    <div id="successAlert" class="alert alert-success alert-dismissible fade show d-none" role="alert">
        Dosen berhasil ditambahkan!
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Form AJAX -->
                    <form method="POST" action="{{ route('dosen.store') }}" id="addDosenForm">
                        @csrf
                        <div class="mb-3">
                            <label for="nidn" class="form-label">NIDN</label>
                            <input type="text" class="form-control" id="nidn" name="nidn" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Dosen</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="gelar" class="form-label">Gelar</label>
                            <input type="text" class="form-control" id="gelar" name="gelar" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="kontak" class="form-label">Kontak</label>
                            <input type="number" class="form-control" id="kontak" name="kontak">
                        </div>
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                        </div>
                        <div class="mb-3">
                            <label for="kompetensi" class="form-label">Kompetensi</label>
                            <input type="text" class="form-control" id="kompetensi" name="kompetensi" required>
                        </div>
                        <div class="mb-3">
                            <label for="prodi" class="form-label">Prodi</label>
                            <select class="form-control" id="prodi" name="prodi" required>
                                <option value="Informatika">Informatika</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script AJAX -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('addDosenForm');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Gagal menyimpan data dosen.');
                return response.json();
            })
            .then(data => {
                // Tampilkan alert sukses
                const alertBox = document.getElementById('successAlert');
                alertBox.classList.remove('d-none');

                // Sembunyikan alert setelah 3 detik
                setTimeout(() => {
                    alertBox.classList.add('d-none');
                }, 3000);

                // Reset form
                form.reset();
                document.getElementById('nidn').focus();
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan saat menambahkan dosen.');
            });
        });
    });
</script>
@endsection
