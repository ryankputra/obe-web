@extends('layouts.app')

@section('title', 'Tambah CPMK')

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
    <h2 class="mb-4 text-purple">Tambah CPMK</h2>

    <div class="p-4 shadow-sm rounded bg-white">
        <form method="POST" action="{{ route('cpmk.store') }}">
            @csrf

            {{-- ... (Semua field form seperti deskripsi, bobot, dll tetap sama) ... --}}
             <div class="mb-3">
                <label for="kode_cpl" class="form-label fw-bold">CPL</label>
                <select class="form-select @error('kode_cpl') is-invalid @enderror" id="kode_cpl" name="kode_cpl" required>
                    <option value="">-- Pilih CPL --</option>
                    @foreach ($cpls as $cpl)
                        <option value="{{ $cpl->kode_cpl }}" {{ old('kode_cpl') == $cpl->kode_cpl ? 'selected' : '' }}>
                            {{ $cpl->kode_cpl }} - {{ $cpl->deskripsi }}
                        </option>
                    @endforeach
                </select>
                @error('kode_cpl') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="kode_cpmk" class="form-label fw-bold">Nomor Urut CPMK</label>
                <input type="number" class="form-control @error('kode_cpmk') is-invalid @enderror"
                    id="kode_cpmk" name="kode_cpmk" min="1" max="999" placeholder="Contoh: 2" value="{{ old('kode_cpmk') }}" required>
                <small class="text-muted">Contoh: 2 dan CPL yang dipilih akan menjadi kode CPMK unik.</small>
                <small id="previewKode" class="form-text text-primary mt-1 d-block"></small>
                @error('kode_cpmk') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="mata_kuliah" class="form-label fw-bold">Mata Kuliah</label>
                <select class="form-select @error('mata_kuliah') is-invalid @enderror" id="mata_kuliah" name="mata_kuliah" required>
                     <option value="">-- Pilih Mata Kuliah --</option>
                    @foreach ($matakuliahs as $matakuliah)
                        <option value="{{ $matakuliah->kode_mk }}" {{ old('mata_kuliah') == $matakuliah->kode_mk ? 'selected' : '' }}>
                            {{ $matakuliah->kode_mk }} - {{ $matakuliah->nama_mk }}
                        </option>
                    @endforeach
                </select>
                @error('mata_kuliah') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                    id="deskripsi" name="deskripsi" rows="5" placeholder="Masukkan deskripsi CPMK" required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="bobot" class="form-label fw-bold">Bobot</label>
                <input type="number" step="0.01" class="form-control @error('bobot') is-invalid @enderror" id="bobot" name="bobot" value="{{ old('bobot') }}" required>
                @error('bobot') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>


            {{-- Implementasi Pencarian Dosen (PIC) --}}
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
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Semua JavaScript dari jawaban sebelumnya diletakkan di sini.
    // Tidak ada perubahan yang diperlukan pada skrip ini.
    const cplSelect=document.getElementById("kode_cpl"),cpmkInput=document.getElementById("kode_cpmk"),preview=document.getElementById("previewKode"),picSearchInput=document.getElementById("pic-search-input"),picSearchResultsTbody=document.getElementById("pic-search-results-tbody"),selectedPicTbody=document.getElementById("selected-pic-tbody"),picHiddenInputsContainer=document.getElementById("pic-hidden-inputs");let selectedPicIdSet=new Set;function updatePreview(){const e=cplSelect.value||"CPLXXX",t=e.replace("CPL",""),n=cpmkInput.value.padStart(3,"0");preview.textContent=`Kode CPMK akan menjadi: CPMK${t}${n}`}function debounce(e,t){let n;return(...o)=>{clearTimeout(n),n=setTimeout(()=>e.apply(this,o),t)}}function addPicHiddenInput(e){picHiddenInputsContainer.querySelector(`input[name="pic_ids[]"][value="${e}"]`)||(e=>{const t=document.createElement("input");t.type="hidden",t.name="pic_ids[]",t.value=e,picHiddenInputsContainer.appendChild(t)})(e)}function removePicHiddenInput(e){(e=>{const t=picHiddenInputsContainer.querySelector(`input[name="pic_ids[]"][value="${e}"]`);t&&t.remove()})(e)}function updatePicSearchResultsTable(){picSearchResultsTbody.querySelectorAll(".add-pic-btn").forEach(e=>{e.disabled=selectedPicIdSet.has(e.getAttribute("data-id"))})}function addPicToSelectedTable(e){const t=String(e.id);selectedPicIdSet.has(t)||selectedPicIdSet.add(t,addPicHiddenInput(e.id),(()=>{const t=document.createElement("tr");t.setAttribute("data-id",e.id),t.innerHTML=`\n            <td>${e.nama}</td>\n            <td>${e.nidn}</td>\n            <td><button type="button" class="btn btn-danger btn-sm remove-selected-pic-btn" data-id="${e.id}">Hapus</button></td>\n        `,selectedPicTbody.appendChild(t)})(),updatePicSearchResultsTable())}function removePicFromSelectedTable(e){const t=String(e);selectedPicIdSet.delete(t),removePicHiddenInput(e),(()=>{const t=selectedPicTbody.querySelector(`tr[data-id="${e}"]`);t&&t.remove()})(),updatePicSearchResultsTable()}function renderPicSearchResults(e){picSearchResultsTbody.innerHTML="",0===e.length?picSearchResultsTbody.innerHTML='<tr><td colspan="3" class="text-center text-muted">Tidak ada dosen ditemukan.</td></tr>':e.forEach(e=>{const t=selectedPicIdSet.has(String(e.id)),n=document.createElement("tr");n.innerHTML=`\n                <td>${e.nama}</td>\n                <td>${e.nidn}</td>\n                <td>\n                    <button type="button" class="btn btn-success btn-sm add-pic-btn" \n                            data-id="${e.id}" data-nama="${e.nama}" data-nidn="${e.nidn}" ${t?"disabled":""}>\n                        Tambah\n                    </button>\n                </td>\n            `,picSearchResultsTbody.appendChild(n)})}async function fetchDosens(e){if(""===e.trim()){picSearchResultsTbody.innerHTML='<tr><td colspan="3" class="text-center text-muted">Ketik untuk mencari dosen.</td></tr>';return}try{const t=await fetch(`{{ route('dosen.search.json') }}?q=${encodeURIComponent(e)}`);if(!t.ok)throw new Error(`Gagal fetch: ${t.status}`);const n=await t.json();renderPicSearchResults(n)}catch(e){console.error("Fetch error:",e),picSearchResultsTbody.innerHTML='<tr><td colspan="3" class="text-center text-danger">Error saat pencarian.</td></tr>'}}picSearchInput.addEventListener("input",debounce(e=>fetchDosens(e.target.value),350)),picSearchResultsTbody.addEventListener("click",e=>{e.target.classList.contains("add-pic-btn")&&(addPicToSelectedTable({id:e.target.dataset.id,nama:e.target.dataset.nama,nidn:e.target.dataset.nidn}),e.target.disabled=!0)}),selectedPicTbody.addEventListener("click",e=>{e.target.classList.contains("remove-selected-pic-btn")&&removePicFromSelectedTable(e.target.dataset.id)}),@if(old('pic_ids'))
@php
$oldDosenData=\App\Models\Dosen::find(old('pic_ids'))->filter();
@endphp
const oldPicsData={!!json_encode($oldDosenData)!!};oldPicsData.forEach(e=>addPicToSelectedTable(e)),@endif
updatePreview();
});
</script>
@endsection