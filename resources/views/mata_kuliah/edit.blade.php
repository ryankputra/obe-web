<div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCourseModalLabel">Edit Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCourseForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id" id="editCourseId">

                    <!-- Kode MK -->
                    <div class="mb-3">
                        <label for="kodeMk" class="form-label">Kode MK</label>
                        <input type="text" class="form-control text-start" id="kodeMk" name="kode_mk">
                        @error('kode_mk')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <!-- Nama MK -->
                    <div class="mb-3">
                        <label for="namaMk" class="form-label">Nama MK</label>
                        <input type="text" class="form-control text-start" id="namaMk" name="nama_mk">
                        @error('nama_mk')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control text-start" id="deskripsi" name="deskripsi" rows="4"></textarea>
                        @error('deskripsi')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <!-- Semester -->
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <input type="number" class="form-control text-start" id="semester" name="semester">
                        @error('semester')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <!-- SKS Teori -->
                    <div class="mb-3">
                        <label for="sksTeori" class="form-label">SKS Teori</label>
                        <input type="number" class="form-control text-start" id="sksTeori" name="sks_teori">
                        @error('sks_teori')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <!-- SKS Praktik -->
                    <div class="mb-3">
                        <label for="sksPraktik" class="form-label">SKS Praktik</label>
                        <input type="number" class="form-control text-start" id="sksPraktik" name="sks_praktik">
                        @error('sks_praktik')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <!-- Status Mata Kuliah -->
                    <div class="mb-3">
                        <label for="statusMataKuliah" class="form-label">Status Mata Kuliah</label>
                        <select class="form-control text-start" id="statusMataKuliah" name="status_mata_kuliah">
                            <option value="Wajib Prodi">Wajib Prodi</option>
                            <option value="Wajib Universitas">Wajib Universitas</option>
                            <option value="Pilihan">Pilihan</option>
                        </select>
                        @error('status_mata_kuliah')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>