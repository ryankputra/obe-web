<div class="d-flex" style="min-height: 100vh;">
    <!-- Sidebar -->
    <div class="bg-primary border-right" id="sidebar-wrapper">
        <div class="sidebar-heading text-white text-center py-4">OBE-WEB</div>
        <div class="list-group list-group-flush flex-grow-1">
            <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
            <a href="{{ route('mata_kuliah.index') }}" class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('mata_kuliah*') ? 'active' : '' }}">
                <i class="fas fa-book me-2"></i> Mata Kuliah
            </a>
            <a href="{{ route('dosen.index') }}" class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('dosen*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher me-2"></i> Dosen
            </a>

            <!-- Prodi dropdown -->
            <div class="list-group-item text-white py-3 my-1 {{ request()->is('fakultasfst*') || request()->is('mahasiswa*') ? 'active' : '' }} dropdown">
                <a class="text-white dropdown-toggle" href="#" id="prodiDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-university me-2"></i> Tambah Data
                </a>
                <ul class="dropdown-menu dropdown-menu-end w-100" aria-labelledby="prodiDropdown" style="background-color: #426c8f;">
                    <li>
                        <a class="dropdown-item text-white {{ request()->is('fakultasfst*') ? 'active' : '' }}" href="{{ route('fakultasfst.index') }}">
                            <i class="fas fa-building me-2"></i> Daftar Prodi
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-white {{ request()->is('mahasiswa*') ? 'active' : '' }}" href="{{ route('mahasiswa.index') }}">
                            <i class="fas fa-user-graduate me-2"></i> Mahasiswa
                        </a>
                    </li>
                </ul>
            </div>

            <a href="{{ route('cpl.index') }}" class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('cpl*') ? 'active' : '' }}">
                <i class="fas fa-list-alt me-2"></i> CPL
            </a>
            <a href="{{ route('cpmk.index') }}" class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('cpmk*') ? 'active' : '' }}">
                <i class="fas fa-tasks me-2"></i> CPMK
            </a>

            <!-- Settings button moved back to bottom -->
            <div class="mt-auto text-center py-3">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog me-2"></i> Pengaturan
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i> Profil</a></li>

                        @if(auth()->user()->role == 'admin')
                            <li><a class="dropdown-item" href="{{ route('users.create') }}"><i class="fas fa-user-plus me-2"></i> Tambah Akun</a></li>
                            <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-users-cog me-2"></i> Kelola Pengguna</a></li>
                            <li><hr class="dropdown-divider"></li>
                        @endif

                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4" id="main-content">
        <!-- Your main content goes here -->
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

<style>
    #sidebar-wrapper {
        background-color: #426c8f;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        width: 250px;
    }

    .sidebar-heading {
        font-size: 1.5rem;
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

    .dropdown-menu {
        background-color: #426c8f;
        color: #fff;
        border: none;
        width: 100%;
    }

    .dropdown-item {
        color: #fff;
    }

    .dropdown-item.active,
    .dropdown-item:hover,
    .dropdown-item:focus {
        background-color: rgb(202, 202, 202);
        color: #000;
    }

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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Optional toggle for sidebar in mobile
    });
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous">
