<div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCourseModalLabel">Tambah Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCourseForm" method="post" action="{{ route('mata_kuliah.store') }}">
                <div class="modal-body">

                    @csrf
                    <div class="mb-3">
                        <label for="kodeMk" class="form-label">Kode MK</label>
                        <input type="text" class="form-control text-start" id="kodeMk" name="kode_mk">
                    </div>
                    <div class="mb-3">
                        <label for="namaMk" class="form-label">Nama MK</label>
                        <input type="text" class="form-control text-start" id="namaMk" name="nama_mk">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control text-start" id="deskripsi" name="deskripsi" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <input type="number" class="form-control text-start" id="semester" name="semester">
                    </div>
                    <div class="mb-3">
                        <label for="sksTeori" class="form-label">SKS Teori</label>
                        <input type="number" class="form-control text-start" id="sksTeori" name="sks_teori">
                    </div>
                    <div class="mb-3">
                        <label for="sksPraktik" class="form-label">SKS Praktik</label>
                        <input type="number" class="form-control text-start" id="sksPraktik" name="sks_praktik">
                    </div>
                    <div class="mb-3">
                        <label for="statusMataKuliah" class="form-label">Status Mata Kuliah</label>
                        <select class="form-control text-start" id="statusMataKuliah" name="status_mata_kuliah">
                            <option value="Wajib Prodi">Wajib Prodi</option>
                            <option value="Wajib Universitas">Wajib Universitas</option>
                            <option value="Wajib Fakultas">Wajib Fakultas</option>
                            <option value="Pilihan">Pilihan</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="addCourseButton">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>