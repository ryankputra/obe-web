<div class="bg-primary border-right" id="sidebar-wrapper">
    <div class="sidebar-heading text-white text-center py-4">OBE-WEB</div>
    <div class="list-group list-group-flush flex-grow-1">
        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action bg-primary text-white py-3 my-1">Dashboard</a>
        <a href="{{ route('mata_kuliah.index') }}" class="list-group-item list-group-item-action bg-primary text-white py-3 my-1">Mata Kuliah</a>
        <a href="{{ route('dosen.index') }}" class="list-group-item list-group-item-action bg-primary text-white py-3 my-1">Dosen</a>
        <a href="{{ route('mahasiswa.index') }}" class="list-group-item list-group-item-action bg-primary text-white py-3 my-1">Mahasiswa</a>
        <a href="{{ route('cpl.index') }}" class="list-group-item list-group-item-action bg-primary text-white py-3 my-1">CPL</a>
        <a href="{{ route('cpmk.index') }}" class="list-group-item list-group-item-action bg-primary text-white py-3 my-1">CPMK</a>
        <div class="mt-auto">
            <!-- Tombol Logout -->
            <a href="#" class="list-group-item list-group-item-action bg-danger text-white py-3 my-1 text-center" style="border-radius: 4px;" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
        </div>
    </div>
</div>

<!-- Form Logout -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Modal Konfirmasi Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin keluar?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('logout-form').submit();">Ya, Logout</button>
            </div>
        </div>
    </div>
</div>
