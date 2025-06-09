@extends('layouts.app')

@section('title', 'Edit CPMK')

@section('styles')
    <style>
        body { background-color: #def4ff; }
        .text-purple { color: #2f5f98; }
        .form-label { color: #2f5f98; font-weight: 500; }
        .form-control, .form-select { border-radius: 0.375rem; }
        .btn-primary { background-color: #2f5f98; border-color: #2f5f98; }
        .btn-primary:hover { background-color: #254d7b; }
        textarea.form-control { min-height: 150px; }
        .sticky-top { top: -1px; }
    </style>
@endsection

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-purple">Edit CPMK</h2>

    <div class="p-4 shadow-sm rounded bg-white">
        {{-- Mengarah ke route 'update' dan menggunakan method 'PUT' --}}
        <form method="POST" action="{{ route('cpmk.update', $cpmk->id) }}">
            @csrf
            @method('PUT')

            {{-- Setiap field diisi dengan data yang ada menggunakan helper 'old()' --}}
            <div class="mb-3">
                <label for="kode_cpl" class="form-label fw-bold">CPL</label>
                <select class="form-select @error('kode_cpl') is-invalid @enderror" id="kode_cpl" name="kode_cpl" required>
                    <option value="">-- Pilih CPL --</option>
                    @foreach ($cpls as $cpl)
                        <option value="{{ $cpl->kode_cpl }}" {{ old('kode_cpl', $cpmk->kode_cpl) == $cpl->kode_cpl ? 'selected' : '' }}>
                            {{ $cpl->kode_cpl }} - {{ $cpl->deskripsi }}
                        </option>
                    @endforeach
                </select>
                @error('kode_cpl') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="kode_cpmk" class="form-label fw-bold">Kode CPMK</label>
                {{-- Dibuat readonly untuk menjaga integritas data --}}
                <input type="text" class="form-control"
                    id="kode_cpmk" name="kode_cpmk" value="{{ old('kode_cpmk', $cpmk->kode_cpmk) }}" readonly>
                <small class="text-muted">Kode CPMK tidak dapat diubah.</small>
            </div>

            <div class="mb-3">
                <label for="mata_kuliah" class="form-label fw-bold">Mata Kuliah</label>
                <select class="form-select @error('mata_kuliah') is-invalid @enderror" id="mata_kuliah" name="mata_kuliah" required>
                     <option value="">-- Pilih Mata Kuliah --</option>
                    @foreach ($matakuliahs as $matakuliah)
                        <option value="{{ $matakuliah->kode_mk }}" {{ old('mata_kuliah', $cpmk->mata_kuliah) == $matakuliah->kode_mk ? 'selected' : '' }}>
                            {{ $matakuliah->kode_mk }} - {{ $matakuliah->nama_mk }}
                        </option>
                    @endforeach
                </select>
                @error('mata_kuliah') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                    id="deskripsi" name="deskripsi" rows="5" placeholder="Masukkan deskripsi CPMK" required>{{ old('deskripsi', $cpmk->deskripsi) }}</textarea>
                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="bobot" class="form-label fw-bold">Bobot</label>
                <input type="number" step="0.01" class="form-control @error('bobot') is-invalid @enderror" id="bobot" name="bobot" value="{{ old('bobot', $cpmk->bobot) }}" required>
                @error('bobot') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Bagian Pencarian PIC (Dosen) tetap sama seperti di create.blade.php --}}
            <div class="mb-3">
                <label for="pic-search-input" class="form-label fw-bold">PIC (Dosen)</label>
                <input type="text" id="pic-search-input" class="form-control" placeholder="Cari dosen berdasarkan nama atau NIDN...">
            </div>
            <div class="table-responsive mb-3" style="max-height: 200px; overflow-y: auto;">
                <table class="table table-sm table-bordered table-hover">
                    <thead class="table-light sticky-top">
                        <tr><th>Nama</th><th>NIDN</th><th>Aksi</th></tr>
                    </thead>
                    <tbody id="pic-search-results-tbody">
                        <tr><td colspan="3" class="text-center text-muted">Ketik untuk mencari dosen.</td></tr>
                    </tbody>
                </table>
            </div>
            <label class="form-label fw-bold">PIC Terpilih:</label>
            <div class="table-responsive" id="selected-pic-container">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr><th>Nama</th><th>NIDN</th><th>Aksi</th></tr>
                    </thead>
                    <tbody id="selected-pic-tbody"></tbody>
                </table>
            </div>
            <div id="pic-hidden-inputs"></div>
            @error('pic_ids') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('cpmk.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seluruh JavaScript dari create.blade.php disalin ke sini
    const picSearchInput=document.getElementById("pic-search-input"),picSearchResultsTbody=document.getElementById("pic-search-results-tbody"),selectedPicTbody=document.getElementById("selected-pic-tbody"),picHiddenInputsContainer=document.getElementById("pic-hidden-inputs");let selectedPicIdSet=new Set;function debounce(e,t){let n;return(...o)=>{clearTimeout(n),n=setTimeout(()=>e.apply(this,o),t)}}function addPicHiddenInput(e){picHiddenInputsContainer.querySelector(`input[name="pic_ids[]"][value="${e}"]`)||(e=>{const t=document.createElement("input");t.type="hidden",t.name="pic_ids[]",t.value=e,picHiddenInputsContainer.appendChild(t)})(e)}function removePicHiddenInput(e){(e=>{const t=picHiddenInputsContainer.querySelector(`input[name="pic_ids[]"][value="${e}"]`);t&&t.remove()})(e)}function updatePicSearchResultsTable(){picSearchResultsTbody.querySelectorAll(".add-pic-btn").forEach(e=>{e.disabled=selectedPicIdSet.has(e.getAttribute("data-id"))})}function addPicToSelectedTable(e){const t=String(e.id);if(selectedPicIdSet.has(t))return;selectedPicIdSet.add(t);addPicHiddenInput(e.id);const n=document.createElement("tr");n.setAttribute("data-id",e.id),n.innerHTML=`\n            <td>${e.nama}</td>\n            <td>${e.nidn}</td>\n            <td><button type="button" class="btn btn-danger btn-sm remove-selected-pic-btn" data-id="${e.id}">Hapus</button></td>\n        `,selectedPicTbody.appendChild(n);updatePicSearchResultsTable()}function removePicFromSelectedTable(e){const t=String(e);selectedPicIdSet.delete(t),removePicHiddenInput(e);const n=selectedPicTbody.querySelector(`tr[data-id="${e}"]`);n&&n.remove(),updatePicSearchResultsTable()}function renderPicSearchResults(e){picSearchResultsTbody.innerHTML="",0===e.length?picSearchResultsTbody.innerHTML='<tr><td colspan="3" class="text-center text-muted">Tidak ada dosen ditemukan.</td></tr>':e.forEach(e=>{const t=selectedPicIdSet.has(String(e.id)),n=document.createElement("tr");n.innerHTML=`\n                <td>${e.nama}</td>\n                <td>${e.nidn}</td>\n                <td>\n                    <button type="button" class="btn btn-success btn-sm add-pic-btn" \n                            data-id="${e.id}" data-nama="${e.nama}" data-nidn="${e.nidn}" ${t?"disabled":""}>\n                        Tambah\n                    </button>\n                </td>\n            `,picSearchResultsTbody.appendChild(n)})}async function fetchDosens(e){if(""===e.trim()){picSearchResultsTbody.innerHTML='<tr><td colspan="3" class="text-center text-muted">Ketik untuk mencari dosen.</td></tr>';return}try{const t=await fetch(`{{ route('dosen.search.json') }}?q=${encodeURIComponent(e)}`);if(!t.ok)throw new Error(`Gagal fetch: ${t.status}`);const n=await t.json();renderPicSearchResults(n)}catch(e){console.error("Fetch error:",e),picSearchResultsTbody.innerHTML='<tr><td colspan="3" class="text-center text-danger">Error saat pencarian.</td></tr>'}}picSearchInput.addEventListener("input",debounce(e=>fetchDosens(e.target.value),350)),picSearchResultsTbody.addEventListener("click",e=>{e.target.classList.contains("add-pic-btn")&&(addPicToSelectedTable({id:e.target.dataset.id,nama:e.target.dataset.nama,nidn:e.target.dataset.nidn}),e.target.disabled=!0)}),selectedPicTbody.addEventListener("click",e=>{e.target.classList.contains("remove-selected-pic-btn")&&removePicFromSelectedTable(e.target.dataset.id)});

    // [DITAMBAHKAN] Script untuk memuat PIC yang sudah ada saat halaman edit dibuka
    const existingPics = {!! json_encode($selectedPics) !!};
    if (existingPics.length > 0) {
        existingPics.forEach(pic => {
            addPicToSelectedTable(pic);
        });
    }

    // Handle jika ada old input dari validation error (untuk memastikan data tidak hilang)
    @if(old('pic_ids'))
        @php
            $oldDosenData = \App\Models\Dosen::find(old('pic_ids'))->filter();
        @endphp
        const oldPicsData = {!! json_encode($oldDosenData) !!};
        oldPicsData.forEach(pic => addPicToSelectedTable(pic));
    @endif
});
</script>
@endsection