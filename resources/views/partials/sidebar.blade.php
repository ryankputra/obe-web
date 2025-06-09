<div class="d-flex" style="min-height: 100vh;">
    <div class="bg-primary border-right" id="sidebar-wrapper">
        <div class="sidebar-heading text-white text-center py-4">OBE-WEB</div>
        <div class="list-group list-group-flush flex-grow-1">

            {{-- Item untuk role 'dosen' --}}
            @if (auth()->check() && auth()->user()->role == 'dosen')
                {{-- Item Dashboard: Ditambahkan untuk role 'dosen' dan diletakkan di atas Penilaian --}}
                <a href="{{ route('dashboard') }}"
                    class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>

                {{-- Item Penilaian: Hanya tampil jika role adalah 'dosen' --}}
                <a href="{{ route('penilaian.index') }}"
                    class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('penilaian*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check me-2"></i> Penilaian
                </a>
            @endif

            {{-- Item Dashboard: Tampil jika BUKAN role 'dosen' --}}
            @if (auth()->check() && auth()->user()->role != 'dosen')
                <a href="{{ route('dashboard') }}"
                    class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            @endif

            {{-- Item Mata Kuliah: Tampil jika BUKAN role 'dosen' --}}
            @if (auth()->check() && auth()->user()->role != 'dosen')
                <a href="{{ route('mata_kuliah.index') }}"
                    class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('mata_kuliah*') ? 'active' : '' }}">
                    <i class="fas fa-book me-2"></i> Mata Kuliah
                </a>
            @endif

            {{-- Item Dosen: Tampil jika BUKAN role 'dosen' --}}
            @if (auth()->check() && auth()->user()->role != 'dosen')
                <a href="{{ route('dosen.index') }}"
                    class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('dosen*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher me-2"></i> Dosen
                </a>
            @endif

            {{-- Item Daftar Prodi: Tampil jika BUKAN role 'dosen' --}}
            @if (auth()->check() && auth()->user()->role != 'dosen')
                <a href="{{ route('fakultasfst.index') }}"
                    class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('fakultasfst*') ? 'active' : '' }}">
                    <i class="fas fa-building me-2"></i> Daftar Prodi
                </a>
            @endif

            {{-- Mahasiswa dropdown: Tampil jika BUKAN role 'dosen' --}}
            @if (auth()->check() && auth()->user()->role != 'dosen')
                <div
                    class="list-group-item text-white py-3 my-1 {{ request()->is('mahasiswa*') ? 'active' : '' }} sidebar-dropdown">
                    <a class="text-white dropdown-toggle text-decoration-none d-block w-100" href="#mahasiswaSubmenu"
                        data-bs-toggle="collapse" aria-expanded="false">
                        <i class="fas fa-user-graduate me-2"></i> Mahasiswa
                    </a>
                    <div class="collapse" id="mahasiswaSubmenu">
                        <div class="list-group mt-2">
                            <a class="list-group-item text-white py-2" href="{{ route('mahasiswa.index') }}">
                                <i class="fas fa-list me-2"></i> Tampil Mahasiswa
                            </a>
                            <a class="list-group-item text-white py-2" href="{{ route('mahasiswa.create') }}">
                                <i class="fas fa-user-plus me-2"></i> Input Mahasiswa
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Item CPL: Tampil jika BUKAN role 'dosen' --}}
            @if (auth()->check() && auth()->user()->role != 'dosen')
                <a href="{{ route('cpl.index') }}"
                    class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('cpl*') ? 'active' : '' }}">
                    <i class="fas fa-list-alt me-2"></i> CPL
                </a>
            @endif

            {{-- Item CPMK: Tampil jika BUKAN role 'dosen' --}}
            @if (auth()->check() && auth()->user()->role != 'dosen')
                <a href="{{ route('cpmk.index') }}"
                    class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('cpmk*') ? 'active' : '' }}">
                    <i class="fas fa-tasks me-2"></i> CPMK
                </a>
            @endif

            {{-- Menu items untuk role 'admin' --}}
            @if (auth()->check() && auth()->user()->role == 'admin')
                {{-- Item Bobot Nilai: Hanya tampil jika role adalah 'admin'. --}}
                <a href="{{ route('bobot_nilai.index') }}"
                    class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('bobot-nilai*') ? 'active' : '' }}">
                    <i class="fas fa-balance-scale me-2"></i> Bobot Nilai
                </a>

                {{-- Item User: Hanya tampil jika role adalah 'admin'. --}}
                <a href="{{ route('users.index') }}"
                    class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('user*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i> User
                </a>
            @endif

            @if (auth()->check())
                {{-- Item Profil: Tampil jika pengguna sudah login --}}
                <a href="{{ route('profile') }}"
                    class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('profile') ? 'active' : '' }}">
                    <i class="fas fa-user me-2"></i> Profil
                </a>

                {{-- Item Logout: Tampil jika pengguna sudah login, memicu modal --}}
                <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"
                    class="list-group-item list-group-item-action text-white py-3 my-1">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            @endif
        </div>
    </div>

    <div class="flex-grow-1 p-4" id="main-content">
        {{-- Konten utama Anda akan dirender di sini --}}
    </div>
</div>

<!-- Logout Modal -->
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

<style>
    #sidebar-wrapper {
        background-color: #426c8f;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        width: 250px;
        position: fixed;
        padding: 20px;
        color: #fff;
    }

    .list-group-item {
        background-color: #426c8f;
        color: #fff;
        border: none;
    }

    .list-group-item:hover,
    .list-group-item:focus {
        background-color: #5b82a6;
        color: #fff;
    }

    .list-group-item.active {
        background-color: #007bff;
        color: #fff;
    }

    /* Make the list group scrollable if content overflows */
    #sidebar-wrapper .list-group.flex-grow-1 {
        overflow-y: auto; /* Enable scrolling */
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }

    /* Hide scrollbar for Chrome, Safari and Opera */
    #sidebar-wrapper .list-group.flex-grow-1::-webkit-scrollbar {
        display: none;
    }

    /* Modal fixes */
    .modal {
        z-index: 1060 !important;
    }
    
    .modal-backdrop {
        z-index: 1050 !important;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        #sidebar-wrapper {
            position: fixed;
            z-index: 1000;
            margin-left: -250px;
            transition: all 0.3s;
        }

        #sidebar-wrapper.active {
            margin-left: 0;
        }

        #main-content {
            width: 100%;
            padding-left: 0;
        }
    }

    /* Dropdown submenu styles */
    .sidebar-dropdown .collapse {
        background-color: rgba(0, 0, 0, 0.1);
    }

    .sidebar-dropdown .list-group-item {
        padding-left: 2.5rem;
        border-radius: 0;
    }

    .sidebar-dropdown .list-group-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
</style>

<!-- Load Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap components
        var dropdownElementList = [].slice.call(document.querySelectorAll('#sidebar-wrapper .collapse'))
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            // Untuk submenu collapse (seperti Mahasiswa)
            return new bootstrap.Collapse(dropdownToggleEl, { toggle: false })
        })

        // Initialize logout modal
        var logoutModalElement = document.getElementById('logoutModal')
        if (logoutModalElement) {
            var logoutModal = new bootstrap.Modal(logoutModalElement);
        }

    })
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">