@extends('layouts.app')

@section('title', 'Tambah CPMK')

@section('styles')
    <style>
        body {
            background-color: #def4ff;
        }

        .text-purple {
            color: #2f5f98;
        }

        .form-label {
            color: #2f5f98;
            font-weight: 500;
        }

        .form-control,
        .form-select {
            border-radius: 0.375rem;
        }

        .btn-primary {
            background-color: #2f5f98;
            border-color: #2f5f98;
        }

        .btn-primary:hover {
            background-color: #254d7b;
        }

        textarea.form-control {
            min-height: 150px;
        }

        .sticky-top {
            top: -1px;
        }
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
                    <select class="form-select @error('kode_cpl') is-invalid @enderror" id="kode_cpl" name="kode_cpl"
                        required>
                        <option value="">-- Pilih CPL --</option>
                        @foreach ($cpls as $cpl)
                            <option value="{{ $cpl->kode_cpl }}" {{ old('kode_cpl') == $cpl->kode_cpl ? 'selected' : '' }}>
                                {{ $cpl->kode_cpl }} - {{ $cpl->deskripsi }}
                            </option>
                        @endforeach
                    </select>
                    @error('kode_cpl')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="kode_cpmk" class="form-label fw-bold">Nomor Urut CPMK</label>
                    <input type="number" class="form-control @error('kode_cpmk') is-invalid @enderror" id="kode_cpmk"
                        name="kode_cpmk" min="1" max="999" oninput="validateCpmkInput(this)"
                        placeholder="Contoh: 2" value="{{ old('kode_cpmk') }}" required>
                    <small class="text-muted">Masukkan angka 1-999</small>
                    <small id="previewKode" class="form-text text-primary mt-1 d-block"></small>
                    <div id="cpmkError" class="invalid-feedback">
                        @error('kode_cpmk')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="mata_kuliah" class="form-label fw-bold">Mata Kuliah</label>
                    <select class="form-select @error('mata_kuliah') is-invalid @enderror" id="mata_kuliah"
                        name="mata_kuliah" required>
                        <option value="">-- Pilih Mata Kuliah --</option>
                        @foreach ($matakuliahs as $matakuliah)
                            <option value="{{ $matakuliah->kode_mk }}"
                                {{ old('mata_kuliah') == $matakuliah->kode_mk ? 'selected' : '' }}>
                                {{ $matakuliah->kode_mk }} - {{ $matakuliah->nama_mk }}
                            </option>
                        @endforeach
                    </select>
                    @error('mata_kuliah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="5"
                        placeholder="Masukkan deskripsi CPMK" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="bobot" class="form-label fw-bold">Bobot</label>
                    <input type="number" step="0.01" class="form-control @error('bobot') is-invalid @enderror"
                        id="bobot" name="bobot" value="{{ old('bobot') }}" required>
                    @error('bobot')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Implementasi Pencarian Dosen (PIC) --}}
                <div class="mb-3">
                    <label for="pic-search-input" class="form-label fw-bold">PIC (Dosen)</label>
                    <input type="text" id="pic-search-input" class="form-control"
                        placeholder="Cari dosen berdasarkan nama atau NIDN...">
                </div>

                <div class="table-responsive mb-3" style="max-height: 200px; overflow-y: auto;">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Nama</th>
                                <th>NIDN</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pic-search-results-tbody">
                            <tr>
                                <td colspan="3" class="text-center text-muted">Ketik untuk mencari dosen.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <label class="form-label fw-bold">PIC Terpilih:</label>
                <div class="table-responsive" id="selected-pic-container">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>NIDN</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="selected-pic-tbody"></tbody>
                    </table>
                </div>

                <div id="pic-hidden-inputs"></div>
                @error('pic_ids')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('cpmk.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cplSelect = document.getElementById("kode_cpl");
            const cpmkInput = document.getElementById("kode_cpmk");
            const preview = document.getElementById("previewKode");

            function updatePreview() {
                const cplCode = cplSelect.value || "CPLXXX";
                const cplNumber = cplCode.replace("CPL", "");
                const cpmkNum = cpmkInput.value || "";
                preview.textContent = `Kode CPMK akan menjadi: CPMK${cplNumber}${cpmkNum}`;
            }

            cplSelect.addEventListener("change", updatePreview);
            cpmkInput.addEventListener("input", updatePreview);
            updatePreview();
        });
    </script>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const picSearchInput = document.getElementById("pic-search-input");
            const picSearchResultsTbody = document.getElementById("pic-search-results-tbody");
            const selectedPicTbody = document.getElementById("selected-pic-tbody");
            const picHiddenInputsContainer = document.getElementById("pic-hidden-inputs");
            let selectedPicIdSet = new Set();

            // Debounce function for search
            function debounce(func, wait) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }

            // Add hidden input for PIC
            function addPicHiddenInput(id) {
                if (!picHiddenInputsContainer.querySelector(`input[name="pic_ids[]"][value="${id}"]`)) {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "pic_ids[]";
                    input.value = id;
                    picHiddenInputsContainer.appendChild(input);
                }
            }

            // Remove hidden input for PIC
            function removePicHiddenInput(id) {
                const input = picHiddenInputsContainer.querySelector(`input[name="pic_ids[]"][value="${id}"]`);
                if (input) input.remove();
            }

            // Update search results table
            function updatePicSearchResultsTable() {
                picSearchResultsTbody.querySelectorAll(".add-pic-btn").forEach(btn => {
                    btn.disabled = selectedPicIdSet.has(btn.getAttribute("data-id"));
                });
            }

            // Add PIC to selected table
            function addPicToSelectedTable(pic) {
                const picId = String(pic.id);
                if (selectedPicIdSet.has(picId)) return;

                selectedPicIdSet.add(picId);
                addPicHiddenInput(pic.id);

                const tr = document.createElement("tr");
                tr.setAttribute("data-id", pic.id);
                tr.innerHTML = `
                <td>${pic.nama}</td>
                <td>${pic.nidn}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-selected-pic-btn" 
                            data-id="${pic.id}">Hapus</button>
                </td>
            `;
                selectedPicTbody.appendChild(tr);
                updatePicSearchResultsTable();
            }

            // Remove PIC from selected table
            function removePicFromSelectedTable(id) {
                const picId = String(id);
                selectedPicIdSet.delete(picId);
                removePicHiddenInput(id);
                const tr = selectedPicTbody.querySelector(`tr[data-id="${id}"]`);
                if (tr) tr.remove();
                updatePicSearchResultsTable();
            }

            // Render search results
            function renderPicSearchResults(dosens) {
                picSearchResultsTbody.innerHTML = "";
                if (dosens.length === 0) {
                    picSearchResultsTbody.innerHTML =
                        '<tr><td colspan="3" class="text-center text-muted">Tidak ada dosen ditemukan.</td></tr>';
                    return;
                }

                dosens.forEach(dosen => {
                    const isSelected = selectedPicIdSet.has(String(dosen.id));
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                    <td>${dosen.nama}</td>
                    <td>${dosen.nidn}</td>
                    <td>
                        <button type="button" class="btn btn-success btn-sm add-pic-btn" 
                                data-id="${dosen.id}" 
                                data-nama="${dosen.nama}" 
                                data-nidn="${dosen.nidn}"
                                ${isSelected ? "disabled" : ""}>
                            Tambah
                        </button>
                    </td>
                `;
                    picSearchResultsTbody.appendChild(tr);
                });
            }

            // Fetch dosens
            async function fetchDosens(query) {
                if (query.trim() === "") {
                    picSearchResultsTbody.innerHTML =
                        '<tr><td colspan="3" class="text-center text-muted">Ketik untuk mencari dosen.</td></tr>';
                    return;
                }

                try {
                    const response = await fetch(
                        `{{ route('dosen.search.json') }}?q=${encodeURIComponent(query)}`);
                    if (!response.ok) throw new Error(`Gagal fetch: ${response.status}`);
                    const data = await response.json();
                    renderPicSearchResults(data);
                } catch (error) {
                    console.error("Fetch error:", error);
                    picSearchResultsTbody.innerHTML =
                        '<tr><td colspan="3" class="text-center text-danger">Error saat pencarian.</td></tr>';
                }
            }

            // Event Listeners
            picSearchInput.addEventListener("input", debounce(e => fetchDosens(e.target.value), 350));

            picSearchResultsTbody.addEventListener("click", e => {
                if (e.target.classList.contains("add-pic-btn")) {
                    addPicToSelectedTable({
                        id: e.target.dataset.id,
                        nama: e.target.dataset.nama,
                        nidn: e.target.dataset.nidn
                    });
                    e.target.disabled = true;
                }
            });

            selectedPicTbody.addEventListener("click", e => {
                if (e.target.classList.contains("remove-selected-pic-btn")) {
                    removePicFromSelectedTable(e.target.dataset.id);
                }
            });

            // Handle if there's old input from validation error
            @if (old('pic_ids'))
                @php
                    $oldDosenData = \App\Models\Dosen::find(old('pic_ids'))->filter();
                @endphp
                const oldPicsData = {!! json_encode($oldDosenData) !!};
                oldPicsData.forEach(pic => addPicToSelectedTable(pic));
            @endif
        });
    </script>

    <script>
        function validateCpmkInput(input) {
            const value = input.value;
            const errorDiv = document.getElementById('cpmkError');

            // Remove any non-numeric characters
            input.value = value.replace(/[^0-9]/g, '');

            if (input.value === '') {
                input.classList.add('is-invalid');
                errorDiv.textContent = 'Nomor urut CPMK harus diisi';
                return false;
            }

            const num = parseInt(input.value);
            if (num < 1 || num > 999) {
                input.classList.add('is-invalid');
                errorDiv.textContent = 'Nomor urut CPMK harus antara 1-999';
                return false;
            }

            input.classList.remove('is-invalid');
            errorDiv.textContent = '';
            return true;
        }

        // Add form submit validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const cpmkInput = document.getElementById('kode_cpmk');
            if (!validateCpmkInput(cpmkInput)) {
                e.preventDefault();
                cpmkInput.focus();
            }
        });
    </script>
@endsection
