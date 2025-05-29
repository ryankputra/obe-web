@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4 text-purple">Kelola Pengguna</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('users.create') }}"
                class="btn btn-success rounded-circle me-2 d-flex justify-content-center align-items-center"
                style="width: 40px; height: 40px;">
                <i class="fas fa-plus text-white"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead style="background-color: #2f5f98; color: #fff;">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Dosen</th> <!-- Add this -->
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="text-start">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                @if ($user->role === 'dosen' && $user->dosen)
                                    {{ $user->dosen->nama }} ({{ $user->dosen->nidn }})
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-warning btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus akun ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        body {
            background-color: #def4ff;
        }

        .table thead {
            background-color: #2f5f98 !important;
            color: #fff !important;
        }

        .table th {
            background-color: #2f5f98 !important;
            color: #fff !important;
        }

        .text-purple {
            color: rgb(0, 0, 0) !important;
        }

        .btn-outline-warning {
            color: #ffc107;
            border-color: #ffc107;
        }

        .btn-outline-warning:hover {
            background-color: #ffc107;
            color: #fff;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
@endsection
