@extends('layout.master')

@section('title', 'Cascading Dropdown Wilayah Indonesia')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-map-marker-radius"></i>
            </span>
            Data Wilayah Administrasi Indonesia
        </h3>
    </div>

    <div class="row">
        <div class="col-12 col-lg-10 col-xl-8 grid-margin">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Cascading Dropdown Wilayah Indonesia</h5>
                </div>
                <div class="card-body">
                    <form id="formWilayah" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="provinsi" class="form-label">Provinsi</label>
                                <select id="provinsi" class="form-select">
                                    <option value="0">Pilih Provinsi</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="kota" class="form-label">Kota/Kabupaten</label>
                                <select id="kota" class="form-select" disabled>
                                    <option value="0">Pilih Kota</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="kecamatan" class="form-label">Kecamatan</label>
                                <select id="kecamatan" class="form-select" disabled>
                                    <option value="0">Pilih Kecamatan</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="kelurahan" class="form-label">Kelurahan/Desa</label>
                                <select id="kelurahan" class="form-select" disabled>
                                    <option value="0">Pilih Kelurahan</option>
                                </select>
                            </div>

                            <div class="col-12 d-flex gap-2 pt-1">
                                <button type="submit" class="btn btn-primary">Cek Data Wilayah</button>
                                <button type="button" id="btnResetWilayah" class="btn btn-light">Reset</button>
                            </div>

                            <div class="col-12">
                                <div id="hasilWilayah" class="alert alert-info d-none mb-0" role="alert"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- ============================
         VERSI 1: jQuery AJAX (NONAKTIF)
         Aktifkan dengan menghapus komentar Blade pada blok ini,
         lalu nonaktifkan blok script Versi 2 di bawah.
         ============================ -->
    {{--
    <script>
        (function () {
            const apiBase = 'https://www.emsifa.com/api-wilayah-indonesia/api';
            const $provinsi = $('#provinsi');
            const $kota = $('#kota');
            const $kecamatan = $('#kecamatan');
            const $kelurahan = $('#kelurahan');

            function resetSelect($el, defaultLabel, isDisabled) {
                $el.html(`<option value="0">${defaultLabel}</option>`);
                $el.prop('disabled', isDisabled);
            }

            function appendOptions($el, data) {
                data.forEach(function (item) {
                    $el.append(`<option value="${item.id}">${item.name}</option>`);
                });
            }

            function loadProvinsi() {
                $.ajax({
                    url: `${apiBase}/provinces.json`,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        appendOptions($provinsi, data);
                    },
                    error: function () {
                        alert('Gagal memuat data provinsi.');
                    }
                });
            }

            $provinsi.on('change', function () {
                const idProvinsi = $(this).val();

                resetSelect($kota, 'Pilih Kota', true);
                resetSelect($kecamatan, 'Pilih Kecamatan', true);
                resetSelect($kelurahan, 'Pilih Kelurahan', true);

                if (idProvinsi === '0') {
                    return;
                }

                $.ajax({
                    url: `${apiBase}/regencies/${idProvinsi}.json`,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        appendOptions($kota, data);
                        $kota.prop('disabled', false);
                    },
                    error: function () {
                        alert('Gagal memuat data kota/kabupaten.');
                    }
                });
            });

            $kota.on('change', function () {
                const idKota = $(this).val();

                resetSelect($kecamatan, 'Pilih Kecamatan', true);
                resetSelect($kelurahan, 'Pilih Kelurahan', true);

                if (idKota === '0') {
                    return;
                }

                $.ajax({
                    url: `${apiBase}/districts/${idKota}.json`,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        appendOptions($kecamatan, data);
                        $kecamatan.prop('disabled', false);
                    },
                    error: function () {
                        alert('Gagal memuat data kecamatan.');
                    }
                });
            });

            $kecamatan.on('change', function () {
                const idKecamatan = $(this).val();

                resetSelect($kelurahan, 'Pilih Kelurahan', true);

                if (idKecamatan === '0') {
                    return;
                }

                $.ajax({
                    url: `${apiBase}/villages/${idKecamatan}.json`,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        appendOptions($kelurahan, data);
                        $kelurahan.prop('disabled', false);
                    },
                    error: function () {
                        alert('Gagal memuat data kelurahan/desa.');
                    }
                });
            });

            resetSelect($kota, 'Pilih Kota', true);
            resetSelect($kecamatan, 'Pilih Kecamatan', true);
            resetSelect($kelurahan, 'Pilih Kelurahan', true);
            loadProvinsi();
        })();
    </script>
    --}}

    <!-- =====================================================
         VERSI 2: Axios (AKTIF)
         Nonaktifkan dengan memberi komentar pada blok <script> ini.
         ===================================================== -->
    <script>
        (function () {
            const apiBase = 'https://www.emsifa.com/api-wilayah-indonesia/api';
            const provinsi = document.getElementById('provinsi');
            const kota = document.getElementById('kota');
            const kecamatan = document.getElementById('kecamatan');
            const kelurahan = document.getElementById('kelurahan');
            const formWilayah = document.getElementById('formWilayah');
            const btnResetWilayah = document.getElementById('btnResetWilayah');
            const hasilWilayah = document.getElementById('hasilWilayah');

            function setLoadingSelect(element, loadingLabel) {
                element.innerHTML = `<option value="0">${loadingLabel}</option>`;
                element.disabled = true;
            }

            function resetSelect(element, defaultLabel, isDisabled) {
                element.innerHTML = `<option value="0">${defaultLabel}</option>`;
                element.disabled = isDisabled;
            }

            function appendOptions(element, data) {
                data.forEach(function (item) {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    element.appendChild(option);
                });
            }

            async function loadProvinsi() {
                setLoadingSelect(provinsi, 'Memuat Provinsi...');

                try {
                    const response = await axios.get(`${apiBase}/provinces.json`);
                    resetSelect(provinsi, 'Pilih Provinsi', false);
                    appendOptions(provinsi, response.data);
                } catch (error) {
                    resetSelect(provinsi, 'Pilih Provinsi', false);
                    alert('Gagal memuat data provinsi.');
                }
            }

            provinsi.addEventListener('change', async function () {
                const idProvinsi = this.value;
                hasilWilayah.classList.add('d-none');

                resetSelect(kota, 'Pilih Kota', true);
                resetSelect(kecamatan, 'Pilih Kecamatan', true);
                resetSelect(kelurahan, 'Pilih Kelurahan', true);

                if (idProvinsi === '0') {
                    return;
                }

                try {
                    setLoadingSelect(kota, 'Memuat Kota/Kabupaten...');
                    const response = await axios.get(`${apiBase}/regencies/${idProvinsi}.json`);
                    resetSelect(kota, 'Pilih Kota', false);
                    appendOptions(kota, response.data);
                } catch (error) {
                    resetSelect(kota, 'Pilih Kota', true);
                    alert('Gagal memuat data kota/kabupaten.');
                }
            });

            kota.addEventListener('change', async function () {
                const idKota = this.value;
                hasilWilayah.classList.add('d-none');

                resetSelect(kecamatan, 'Pilih Kecamatan', true);
                resetSelect(kelurahan, 'Pilih Kelurahan', true);

                if (idKota === '0') {
                    return;
                }

                try {
                    setLoadingSelect(kecamatan, 'Memuat Kecamatan...');
                    const response = await axios.get(`${apiBase}/districts/${idKota}.json`);
                    resetSelect(kecamatan, 'Pilih Kecamatan', false);
                    appendOptions(kecamatan, response.data);
                } catch (error) {
                    resetSelect(kecamatan, 'Pilih Kecamatan', true);
                    alert('Gagal memuat data kecamatan.');
                }
            });

            kecamatan.addEventListener('change', async function () {
                const idKecamatan = this.value;
                hasilWilayah.classList.add('d-none');

                resetSelect(kelurahan, 'Pilih Kelurahan', true);

                if (idKecamatan === '0') {
                    return;
                }

                try {
                    setLoadingSelect(kelurahan, 'Memuat Kelurahan...');
                    const response = await axios.get(`${apiBase}/villages/${idKecamatan}.json`);
                    resetSelect(kelurahan, 'Pilih Kelurahan', false);
                    appendOptions(kelurahan, response.data);
                } catch (error) {
                    resetSelect(kelurahan, 'Pilih Kelurahan', true);
                    alert('Gagal memuat data kelurahan/desa.');
                }
            });

            formWilayah.addEventListener('submit', function (event) {
                event.preventDefault();

                if (
                    provinsi.value === '0' ||
                    kota.value === '0' ||
                    kecamatan.value === '0' ||
                    kelurahan.value === '0'
                ) {
                    hasilWilayah.className = 'alert alert-warning mb-0';
                    hasilWilayah.textContent = 'Lengkapi pilihan wilayah sampai Kelurahan/Desa terlebih dahulu.';
                    return;
                }

                const teksProvinsi = provinsi.options[provinsi.selectedIndex].text;
                const teksKota = kota.options[kota.selectedIndex].text;
                const teksKecamatan = kecamatan.options[kecamatan.selectedIndex].text;
                const teksKelurahan = kelurahan.options[kelurahan.selectedIndex].text;

                hasilWilayah.className = 'alert alert-success mb-0';
                hasilWilayah.textContent = `Wilayah dipilih: ${teksProvinsi} / ${teksKota} / ${teksKecamatan} / ${teksKelurahan}`;
            });

            btnResetWilayah.addEventListener('click', function () {
                resetSelect(provinsi, 'Pilih Provinsi', false);
                resetSelect(kota, 'Pilih Kota', true);
                resetSelect(kecamatan, 'Pilih Kecamatan', true);
                resetSelect(kelurahan, 'Pilih Kelurahan', true);
                hasilWilayah.className = 'alert alert-info d-none mb-0';
                hasilWilayah.textContent = '';
                loadProvinsi();
            });

            resetSelect(kota, 'Pilih Kota', true);
            resetSelect(kecamatan, 'Pilih Kecamatan', true);
            resetSelect(kelurahan, 'Pilih Kelurahan', true);
            loadProvinsi();
        })();
    </script>
@endpush
