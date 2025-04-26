<div class="bg-primary border-right" id="sidebar-wrapper">
    <div class="sidebar-heading text-white text-center py-4">OBE-WEB</div>
    <div class="list-group list-group-flush flex-grow-1">
        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
<a href="{{ route('mata_kuliah.index') }}" class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('mata_kuliah*') ? 'active' : '' }}">Mata Kuliah</a>
<a href="{{ route('dosen.index') }}" class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('dosen*') ? 'active' : '' }}">Dosen</a>
<a href="{{ route('fakultasfst.index') }}" class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('fakultasfst*') ? 'active' : '' }}">Prodi</a>
<a href="{{ route('cpl.index') }}" class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('cpl*') ? 'active' : '' }}">CPL</a>
<a href="{{ route('cpmk.index') }}" class="list-group-item list-group-item-action text-white py-3 my-1 {{ request()->is('cpmk*') ? 'active' : '' }}">CPMK</a>

        <div class="mt-auto text-center">
            <!-- Tombol Gerigi untuk Profil dan Logout -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile') }}">Profil</a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a></li>
                </ul>
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

<style>
    #sidebar-wrapper {
        background-color: #426c8f;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .sidebar-heading {
        font-size: 1.5rem;
        padding: 20px;
        color: #fff;
    }

    .list-group-item {
        background-color: #426c8f;
        color: #fff;
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

    .dropdown {
        position: absolute;
        bottom: 20px;
        left: 20px;
    }

    .dropdown-toggle::after {
        display: none;
    }

    .dropdown-menu {
        background-color: #426c8f;
        color: #fff;
    }

    .dropdown-item {
        color: #fff;
    }

    .dropdown-item:hover,
    .dropdown-item:focus {
        background-color:rgb(202, 202, 202);
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous">
