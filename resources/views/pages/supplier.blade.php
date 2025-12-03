<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Manajemen Data Supplier</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{asset('vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" />
</head>

<body>
    <div class="container-scroller">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
                <a class="sidebar-brand brand-logo" href="index.html"><img src="{{asset('images/logo.svg')}}" alt="logo" /></a>
                <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="{{asset('images/logo-mini.svg')}}" alt="logo" /></a>
            </div>
            <ul class="nav">
                <li class="nav-item profile">
                    <div class="profile-desc">
                        <div class="profile-pic">
                            <div class="count-indicator">
                                <img class="img-xs rounded-circle " src="{{asset('images/faces/face15.jpg')}}" alt="">
                                <span class="count bg-success"></span>
                            </div>
                            <div class="profile-name">
                                <h5 class="mb-0 font-weight-normal">Henry Klein</h5>
                                <span>Gold Member</span>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item nav-category"><span class="nav-link">Navigation</span></li>
                <li class="nav-item menu-items"><a class="nav-link" href="/"><span class="menu-icon"><i class="mdi mdi-book-open-variant"></i></span><span class="menu-title">Dashboard</span></a></li>
                <li class="nav-item menu-items"><a class="nav-link" href="/apoteker"><span class="menu-icon"><i class="mdi mdi-account-circle"></i></span><span class="menu-title">Data Apoteker</span></a></li>
                <li class="nav-item menu-items"><a class="nav-link" href="/obat"><span class="menu-icon"><i class="mdi mdi-pill"></i></span><span class="menu-title">Data Obat</span></a></li>
                <li class="nav-item menu-items active"><a class="nav-link" href="/supplier"><span class="menu-icon"><i class="mdi mdi-ambulance"></i></span><span class="menu-title">Data Supplier</span></a></li>
                <li class="nav-item menu-items"><a class="nav-link" href="/pelanggan"><span class="menu-icon"><i class="mdi mdi-account-multiple"></i></span><span class="menu-title">Data Pelanggan</span></a></li>
            </ul>
        </nav>
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Data Supplier</h4>
                                    <p class="card-description">Daftar supplier yang terdaftar.</p>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-primary btn-fw" data-toggle="modal" data-target="#dataModal">
                                                <i class="mdi mdi-plus"></i> Tambah Supplier
                                            </button>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <select class="form-control" id="filterKota">
                                                            <option value="">Semua Kota</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="text" id="searchInput" class="form-control" placeholder="Cari nama supplier...">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Kode</th>
                                                    <th>Nama Supplier</th>
                                                    <th>Alamat</th>
                                                    <th>Kota</th>
                                                    <th>Telepon</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="dataTableBody">
                                                </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <div id="dataInfo">Menampilkan 0 dari 0 data</div>
                                        <nav>
                                            <ul class="pagination" id="pagination"></ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataModalLabel">Tambah Data Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addDataForm">
                        <div class="form-group">
                            <label for="nama">Nama Supplier</label>
                            <input type="text" class="form-control" name="nama" id="nama" placeholder="Contoh: PT. Kimia Farma" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" name="alamat" id="alamat" rows="3" placeholder="Contoh: Jl. Jenderal Sudirman No. 10" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="kota">Kota</label>
                            <input type="text" class="form-control" name="kota" id="kota" placeholder="Contoh: Jakarta" required>
                        </div>
                        <div class="form-group">
                            <label for="telepon">Telepon</label>
                            <input type="tel" class="form-control" name="telepon" id="telepon" placeholder="Contoh: 081234567890" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
    <script>
        $(document).ready(function() {
            // [UBAH] Sesuaikan URL API ke rute supplier
            const API_URL = "{{ route('supplier.data') }}";
            const STORE_URL = "{{ route('supplier.store') }}";
            const FILTER_API_URL = "{{ route('supplier.filters') }}";

            function fetchData(page = 1) {
                const search = $('#searchInput').val();
                const kota = $('#filterKota').val(); // Filter berdasarkan kota

                $.ajax({
                    url: API_URL,
                    type: 'GET',
                    data: {
                        page: page,
                        search: search,
                        kota: kota // Kirim parameter kota
                    },
                    success: function(response) {
                        renderTable(response.data);
                        renderPagination(response);
                        updateDataInfo(response);
                    },
                    error: function(err) {
                        $('#dataTableBody').html('<tr><td colspan="6" class="text-center text-danger">Gagal memuat data.</td></tr>');
                    }
                });
            }

            function populateFilters() {
                $.get(FILTER_API_URL, function(response) {
                    const kotaSelect = $('#filterKota');
                    // Asumsikan API mengembalikan { kota: [...] }
                    if (response.kota) {
                        response.kota.forEach(kota => {
                           kotaSelect.append(`<option value="${kota}">${kota}</option>`);
                        });
                    }
                });
            }
            
            // [UBAH] renderTable untuk menampilkan data supplier
            function renderTable(data) {
                const tableBody = $('#dataTableBody');
                tableBody.empty();
                if (data.length === 0) {
                    // Sesuaikan colspan dengan jumlah kolom baru
                    tableBody.append('<tr><td colspan="6" class="text-center">Data supplier tidak ditemukan.</td></tr>');
                    return;
                }
                data.forEach(item => {
                    const rowHtml = `
                        <tr>
                            <td><span class="badge badge-dark">${item.kode_supplier}</span></td>
                            <td>${item.nama}</td>
                            <td>${item.alamat}</td>
                            <td>${item.kota}</td>
                            <td>${item.telepon}</td>
                            <td>
                                <button class="btn btn-sm btn-warning"><i class="mdi mdi-pencil"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
                            </td>
                        </tr>
                    `;
                    tableBody.append(rowHtml);
                });
            }

            function renderPagination(paginator) {
                const pagination = $('#pagination');
                pagination.empty();
                if (!paginator.links || paginator.links.length <= 3) return;
                paginator.links.forEach(link => {
                    let label = link.label.replace('&laquo; Previous', 'Previous').replace('Next &raquo;', 'Next');
                    pagination.append(`
                        <li class="page-item ${link.active ? 'active' : ''} ${link.url === null ? 'disabled' : ''}">
                            <a class="page-link" href="#" data-page="${link.url ? new URL(link.url).searchParams.get('page') : ''}">${label}</a>
                        </li>
                    `);
                });
            }

            function updateDataInfo(paginator) {
                $('#dataInfo').text(`Menampilkan ${paginator.from || 0} - ${paginator.to || 0} dari ${paginator.total} data`);
            }

            // Event Listeners
            // [UBAH] Targetkan filter kota
            $('#searchInput, #filterKota').on('keyup change', function() {
                fetchData(1);
            });

            $('#pagination').on('click', 'a', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (page) fetchData(page);
            });

            $('#addDataForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: STORE_URL,
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        if(response.success) {
                            $('#dataModal').modal('hide');
                            $('#addDataForm')[0].reset();
                            fetchData(1);
                        }
                    },
                    error: function(err) {
                        // Implementasikan error handling, misal menampilkan pesan validasi
                        alert('Gagal menyimpan data!');
                    }
                });
            });

            // Inisialisasi
            populateFilters();
            fetchData();
        });
    </script>
</body>
</html>