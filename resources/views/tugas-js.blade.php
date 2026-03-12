@extends('layout.master')

@section('title', 'Tugas JS - Native & jQuery')

@push('head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <style>
        .table-hover-pointer tbody tr:hover {
            cursor: pointer;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-code-tags"></i>
            </span>
            Tugas JavaScript Native & jQuery
        </h3>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card mb-4">
                <div class="card-header fw-semibold">Form Input Barang</div>
                <div class="card-body">
                    <form id="formBarang" novalidate>
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label for="namaBarang" class="form-label">Nama barang</label>
                                <input type="text" id="namaBarang" class="form-control" required>
                            </div>
                            <div class="col-md-5">
                                <label for="hargaBarang" class="form-label">Harga barang</label>
                                <input type="number" id="hargaBarang" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="button" id="btnSubmitBarang" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-12 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header fw-semibold">Tabel HTML Biasa</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover-pointer mb-0" id="tabelBiasa">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Harga Barang</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header fw-semibold">Tabel DataTables</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover-pointer mb-0" id="tabelDataTables">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Harga Barang</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header fw-semibold">Select</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="inputKotaBiasa" class="form-label">Nama kota</label>
                                <input type="text" id="inputKotaBiasa" class="form-control" placeholder="Contoh: Bandung">
                            </div>
                            <div class="mb-3 d-grid d-md-inline-block">
                                <button type="button" id="btnTambahKotaBiasa" class="btn btn-outline-primary">Tambahkan</button>
                            </div>
                            <div class="mb-3">
                                <label for="selectKotaBiasa" class="form-label">Daftar kota</label>
                                <select id="selectKotaBiasa" class="form-select">
                                    <option value="" selected disabled>Pilih kota...</option>
                                </select>
                            </div>
                            <p class="mb-0">Kota Terpilih: <span id="teksKotaBiasa"></span></p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header fw-semibold">select 2</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="inputKotaSelect2" class="form-label">Nama kota</label>
                                <input type="text" id="inputKotaSelect2" class="form-control" placeholder="Contoh: Surabaya">
                            </div>
                            <div class="mb-3 d-grid d-md-inline-block">
                                <button type="button" id="btnTambahKotaSelect2" class="btn btn-outline-primary">Tambahkan</button>
                            </div>
                            <div class="mb-3">
                                <label for="selectKotaSelect2" class="form-label">Daftar kota</label>
                                <select id="selectKotaSelect2" class="form-select">
                                    <option value="" selected disabled>Pilih kota...</option>
                                </select>
                            </div>
                            <p class="mb-0">Kota Terpilih: <span id="teksKotaSelect2"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBarang" tabindex="-1" aria-labelledby="modalBarangLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBarangLabel">Edit / Hapus Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formModalBarang" novalidate>
                        <div class="mb-3">
                            <label for="modalIdBarang" class="form-label">ID Barang</label>
                            <input type="text" id="modalIdBarang" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="modalNamaBarang" class="form-label">Nama Barang</label>
                            <input type="text" id="modalNamaBarang" class="form-control" required>
                        </div>
                        <div class="mb-0">
                            <label for="modalHargaBarang" class="form-label">Harga Barang</label>
                            <input type="number" id="modalHargaBarang" class="form-control" min="0" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnUbahBarang" class="btn btn-primary">Ubah</button>
                    <button type="button" id="btnHapusBarang" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        if (!window.__tugasJsInitialized) {
            window.__tugasJsInitialized = true;

            let counterIdBarang = 1;
            let selectedBarangId = null;
            let selectedRowBiasa = null;
            let selectedRowDataTables = null;

            const formBarang = document.getElementById('formBarang');
            const btnSubmitBarang = document.getElementById('btnSubmitBarang');
            const inputNamaBarang = document.getElementById('namaBarang');
            const inputHargaBarang = document.getElementById('hargaBarang');
            const tbodyBiasa = document.querySelector('#tabelBiasa tbody');

            const formModalBarang = document.getElementById('formModalBarang');
            const modalIdBarang = document.getElementById('modalIdBarang');
            const modalNamaBarang = document.getElementById('modalNamaBarang');
            const modalHargaBarang = document.getElementById('modalHargaBarang');

            const modalBarangEl = document.getElementById('modalBarang');
            const modalBarangInstance = new bootstrap.Modal(modalBarangEl);

            const dataTable = $('#tabelDataTables').DataTable({
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    paginate: {
                        previous: 'Sebelumnya',
                        next: 'Berikutnya'
                    }
                }
            });

            $('#selectKotaSelect2').select2({
                placeholder: 'Pilih kota...',
                allowClear: true,
                width: '100%'
            });

            function generateBarangId() {
                return `BRG-${String(counterIdBarang++).padStart(4, '0')}`;
            }

            function resetSubmitButton() {
                btnSubmitBarang.disabled = false;
                btnSubmitBarang.innerHTML = 'Submit';
            }

            function setLoadingSubmitButton() {
                btnSubmitBarang.disabled = true;
                btnSubmitBarang.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Loading...
                `;
            }

            function sinkronkanReferensiById(idBarang) {
                selectedBarangId = idBarang;
                selectedRowBiasa = tbodyBiasa.querySelector(`tr[data-id="${idBarang}"]`);

                selectedRowDataTables = null;
                dataTable.rows().every(function () {
                    const rowData = this.data();
                    if (rowData && rowData[0] === idBarang) {
                        selectedRowDataTables = this;
                        return false;
                    }
                });
            }

            function isiModalDariData(idBarang, namaBarang, hargaBarang) {
                sinkronkanReferensiById(idBarang);
                modalIdBarang.value = idBarang;
                modalNamaBarang.value = namaBarang;
                modalHargaBarang.value = hargaBarang;
                modalBarangInstance.show();
            }

            btnSubmitBarang.addEventListener('click', function () {
                if (!formBarang.checkValidity()) {
                    formBarang.reportValidity();
                    return;
                }

                setLoadingSubmitButton();

                const namaBarang = inputNamaBarang.value.trim();
                const hargaBarang = inputHargaBarang.value.trim();

                setTimeout(function () {
                    const idBarang = generateBarangId();

                    const tr = document.createElement('tr');
                    tr.setAttribute('data-id', idBarang);
                    tr.innerHTML = `
                        <td>${idBarang}</td>
                        <td>${namaBarang}</td>
                        <td>${hargaBarang}</td>
                    `;
                    tbodyBiasa.appendChild(tr);

                    dataTable.row.add([idBarang, namaBarang, hargaBarang]).draw(false);

                    formBarang.reset();
                    resetSubmitButton();
                }, 1000);
            });

            $('#tabelBiasa tbody').on('click', 'tr', function () {
                const cells = $(this).children('td');
                const idBarang = $(cells[0]).text().trim();
                const namaBarang = $(cells[1]).text().trim();
                const hargaBarang = $(cells[2]).text().trim();

                isiModalDariData(idBarang, namaBarang, hargaBarang);
            });

            $('#tabelDataTables tbody').on('click', 'tr', function () {
                const rowApi = dataTable.row(this);
                const rowData = rowApi.data();
                if (!rowData) {
                    return;
                }

                const idBarang = rowData[0];
                const namaBarang = rowData[1];
                const hargaBarang = rowData[2];

                isiModalDariData(idBarang, namaBarang, hargaBarang);
            });

            document.getElementById('btnHapusBarang').addEventListener('click', function () {
                if (!selectedBarangId) {
                    return;
                }

                if (selectedRowBiasa) {
                    selectedRowBiasa.remove();
                }

                if (selectedRowDataTables) {
                    selectedRowDataTables.remove().draw(false);
                } else {
                    dataTable.rows().every(function () {
                        const rowData = this.data();
                        if (rowData && rowData[0] === selectedBarangId) {
                            this.remove();
                        }
                    });
                    dataTable.draw(false);
                }

                selectedBarangId = null;
                selectedRowBiasa = null;
                selectedRowDataTables = null;
                modalBarangInstance.hide();
            });

            document.getElementById('btnUbahBarang').addEventListener('click', function () {
                if (!formModalBarang.checkValidity()) {
                    formModalBarang.reportValidity();
                    return;
                }

                if (!selectedBarangId) {
                    return;
                }

                const namaBaru = modalNamaBarang.value.trim();
                const hargaBaru = modalHargaBarang.value.trim();

                if (selectedRowBiasa) {
                    const cells = selectedRowBiasa.children;
                    cells[1].textContent = namaBaru;
                    cells[2].textContent = hargaBaru;
                }

                if (selectedRowDataTables) {
                    selectedRowDataTables.data([selectedBarangId, namaBaru, hargaBaru]).draw(false);
                } else {
                    dataTable.rows().every(function () {
                        const rowData = this.data();
                        if (rowData && rowData[0] === selectedBarangId) {
                            this.data([selectedBarangId, namaBaru, hargaBaru]);
                        }
                    });
                    dataTable.draw(false);
                }

                modalBarangInstance.hide();
            });

            function tambahKota(inputSelector, selectSelector) {
                const inputEl = $(inputSelector);
                const selectEl = $(selectSelector);
                const namaKota = inputEl.val().trim();

                if (!namaKota) {
                    return;
                }

                const opsiBaru = new Option(namaKota, namaKota, false, false);
                selectEl.append(opsiBaru).trigger('change');
                inputEl.val('');
            }

            $('#btnTambahKotaBiasa').on('click', function () {
                tambahKota('#inputKotaBiasa', '#selectKotaBiasa');
            });

            $('#btnTambahKotaSelect2').on('click', function () {
                tambahKota('#inputKotaSelect2', '#selectKotaSelect2');
            });

            $('#selectKotaBiasa').on('change', function () {
                $('#teksKotaBiasa').text($(this).val() || '');
            });

            $('#selectKotaSelect2').on('change', function () {
                $('#teksKotaSelect2').text($(this).val() || '');
            });
        }
    </script>
@endsection
