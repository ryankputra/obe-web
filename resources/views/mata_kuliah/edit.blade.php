<!-- Edit Mata Kuliah Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCourseModalLabel">Edit Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCourseForm" method="POST">
                @csrf
                @method('PUT') <!-- Gunakan metode PUT untuk update -->
                <input type="hidden" id="edit_id" name="id"> <!-- Hidden field untuk ID -->
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_kode_mk" class="form-label">Kode MK</label>
                            <input type="text" class="form-control" id="edit_kode_mk" name="kode_mk" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_nama_mk" class="form-label">Nama MK</label>
                            <input type="text" class="form-control" id="edit_nama_mk" name="nama_mk" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="edit_semester" class="form-label">Semester</label>
                            <select class="form-select" id="edit_semester" name="semester" required>
                                <option value="">Pilih Semester</option>
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_sks_teori" class="form-label">SKS Teori</label>
                            <input type="number" class="form-control" id="edit_sks_teori" name="sks_teori" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_sks_praktik" class="form-label">SKS Praktik</label>
                            <input type="number" class="form-control" id="edit_sks_praktik" name="sks_praktik" min="0" required>
                        </div>
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

<!-- JavaScript to Populate the Edit Form -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.body.addEventListener('click', function (event) {
        if (event.target.matches('.edit-course')) {
            event.preventDefault();

            // Ambil data dari tombol yang diklik
            const button = event.target;
            const id = button.getAttribute('data-id');
            const kodeMk = button.getAttribute('data-kode');
            const namaMk = button.getAttribute('data-nama');
            const deskripsi = button.getAttribute('data-deskripsi');
            const semester = button.getAttribute('data-semester');
            const sksTeori = button.getAttribute('data-teori');
            const sksPraktik = button.getAttribute('data-praktik');

            // Isi form dengan data yang akan diedit
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_kode_mk').value = kodeMk;
            document.getElementById('edit_nama_mk').value = namaMk;
            document.getElementById('edit_deskripsi').value = deskripsi;
            document.getElementById('edit_semester').value = semester;
            document.getElementById('edit_sks_teori').value = sksTeori;
            document.getElementById('edit_sks_praktik').value = sksPraktik;

            // Perbarui form action sesuai dengan ID
            const form = document.getElementById('editCourseForm');
            form.action = `/editMk/${id}`; // Sesuaikan dengan route di Laravel

            // Tampilkan modal
            const editModal = new bootstrap.Modal(document.getElementById('editCourseModal'));
            editModal.show();
        }
    });
});
</script>
