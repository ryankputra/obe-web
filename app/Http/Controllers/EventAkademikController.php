<?php

namespace App\Http\Controllers;

use App\Models\EventAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk pengecekan role

class EventAkademikController extends Controller
{
    // Tambahkan middleware 'admin' di constructor jika Anda tidak mengelompokkan rute di web.php
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'admin']); // Pastikan Anda memiliki middleware 'admin' yang terdaftar
    // }

    /**
     * Tampilkan daftar event akademik.
     */
    public function index()
    {
        // Pengecekan role di controller jika tidak di route middleware group
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin.');
        }

        $events = EventAkademik::orderBy('tanggal_event', 'desc')->paginate(10);

        return view('events_akademik.index', compact('events'));
    }

    /**
     * Tampilkan form untuk membuat event akademik baru.
     */
    public function create()
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin.');
        }
        return view('events_akademik.create');
    }

    /**
     * Simpan event akademik baru ke database.
     */
    public function store(Request $request)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin.');
        }

        $request->validate([
            'tanggal_event' => 'required|date',
            'deskripsi' => 'required|string|max:255',
            'tipe' => 'nullable|string|in:info,success,warning,danger', // Sesuaikan dengan tipe yang Anda inginkan
        ]);

        EventAkademik::create($request->all());

        return redirect()->route('event_akademik.index')->with('success', 'Event akademik berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail event akademik (opsional).
     */
    public function show(EventAkademik $event)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin.');
        }
        return view('events_akademik.show', compact('event'));
    }

    /**
     * Tampilkan form untuk mengedit event akademik yang sudah ada.
     */
    public function edit(EventAkademik $event)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin.');
        }
        return view('events_akademik.edit', compact('event'));
    }

    /**
     * Perbarui event akademik di database.
     */
    public function update(Request $request, EventAkademik $event)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin.');
        }

        $request->validate([
            'tanggal_event' => 'required|date',
            'deskripsi' => 'required|string|max:255',
            'tipe' => 'nullable|string|in:info,success,warning,danger',
        ]);

        $event->update($request->all());

        return redirect()->route('event_akademik.index')->with('success', 'Event akademik berhasil diperbarui!');
    }

    /**
     * Hapus event akademik dari database.
     */
    public function destroy(EventAkademik $event)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin.');
        }

        $event->delete();

        return redirect()->route('event_akademik.index')->with('success', 'Event akademik berhasil dihapus!');
    }
}