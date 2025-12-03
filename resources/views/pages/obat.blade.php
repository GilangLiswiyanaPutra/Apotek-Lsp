<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Manajemen Data Obat</title>
  <link rel="stylesheet" href="{{asset('vendors/mdi/css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/jvectormap/jquery-jvectormap.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/flag-icon-css/css/flag-icon.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/owl-carousel-2/owl.carousel.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/owl-carousel-2/owl.theme.default.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/style.css')}}">
  <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" />
  <style>
    /* Menambahkan pointer pada baris tabel agar terlihat bisa di-hover */
    .table-hover tbody tr:hover {
      cursor: pointer;
    }
  </style>
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
          <li class="nav-item menu-items active"><a class="nav-link" href="/obat"><span class="menu-icon"><i class="mdi mdi-pill"></i></span><span class="menu-title">Data Obat</span></a></li>
          <li class="nav-item menu-items"><a class="nav-link" href="/supplier"><span class="menu-icon"><i class="mdi mdi-ambulance"></i></span><span class="menu-title">Data Supplier</span></a></li>
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
                  <h4 class="card-title">Data Obat</h4>
                  <p class="card-description">Daftar obat yang tersedia.</p>
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <button type="button" class="btn btn-primary btn-fw" data-toggle="modal" data-target="#dataModal">
                        <i class="mdi mdi-plus"></i> Tambah Data
                      </button>
                    </div>
                    <div class="col-md-9">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                             <select class="form-control" id="filterJenis">
                                <option value="">Semua Jenis</option>
                             </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                             <select class="form-control" id="filterSupplier">
                                <option value="">Semua Supplier</option>
                             </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari nama obat...">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Obat</th>
                          <th>Jenis</th>
                          <th>Satuan</th>
                          <th>Harga Jual</th>
                          <th>Stok</th>
                          <th>Supplier</th>
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
                      <ul class="pagination" id="pagination">
                        </ul>
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
          <h5 class="modal-title" id="dataModalLabel">Tambah Data Obat</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="addDataForm">
            <div class="form-group">
              <label for="namaObat">Nama Obat</label>
              <input type="text" class="form-control" id="namaObat" placeholder="Contoh: Paracetamol" required>
            </div>
            <div class="form-group">
              <label for="jenisObat">Jenis Obat</label>
              <input type="text" class="form-control" id="jenisObat" placeholder="Contoh: Tablet" required>
            </div>
            <div class="form-group">
              <label for="satuanObat">Satuan</label>
              <input type="text" class="form-control" id="satuanObat" placeholder="Contoh: Strip" required>
            </div>
            <div class="form-group">
              <label for="hargaJual">Harga Jual (Rp)</label>
              <input type="number" class="form-control" id="hargaJual" placeholder="Contoh: 15000" required>
            </div>
             <div class="form-group">
              <label for="hargaBeli">Harga Beli (Rp)</label>
              <input type="number" class="form-control" id="hargaBeli" placeholder="Contoh: 12000" required>
            </div>
            <div class="form-group">
              <label for="stokObat">Stok</label>
              <input type="number" class="form-control" id="stokObat" placeholder="Contoh: 100" required>
            </div>
            <div class="form-group">
              <label for="supplierObat">Supplier</label>
              <input type="text" class="form-control" id="supplierObat" placeholder="Contoh: PT. Kimia Farma" required>
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
    const API_URL = "{{ route('obat.data') }}";
    const STORE_URL = "{{ route('obat.store') }}";
    const FILTER_API_URL = "{{ route('obat.filters') }}";

    // Fungsi utama untuk mengambil data dari server
    function fetchData(page = 1) {
        const search = $('#searchInput').val();
        const jenis = $('#filterJenis').val();
        const supplier = $('#filterSupplier').val();

        $.ajax({
            url: API_URL,
            type: 'GET',
            data: {
                page: page,
                search: search,
                jenis: jenis,
                supplier: supplier
            },
            success: function(response) {
                renderTable(response.data); // 'data' adalah array obat dari paginator
                renderPagination(response); // kirim seluruh objek paginator
                updateDataInfo(response);
            },
            error: function(err) {
                console.error("Gagal mengambil data:", err);
                $('#dataTableBody').html('<tr><td colspan="8" class="text-center text-danger">Gagal memuat data.</td></tr>');
            }
        });
    }
    
    // Fungsi untuk mengisi dropdown filter
    function populateFilters() {
        $.get(FILTER_API_URL, function(response) {
            const jenisSelect = $('#filterJenis');
            response.jenis.forEach(jenis => {
                jenisSelect.append(`<option value="${jenis}">${jenis}</option>`);
            });
            
            const supplierSelect = $('#filterSupplier');
            response.suppliers.forEach(supplier => {
                supplierSelect.append(`<option value="${supplier}">${supplier}</option>`);
            });
        });
    }

    // Fungsi untuk merender tabel (sedikit diubah)
    function renderTable(data) {
        const tableBody = $('#dataTableBody');
        tableBody.empty();
        if (data.length === 0) {
            tableBody.append('<tr><td colspan="8" class="text-center">Data tidak ditemukan.</td></tr>');
            return;
        }
        data.forEach((item, index) => {
            const rowHtml = `
                <tr>
                    <td>${item.id}</td>
                    <td>${item.nama}</td>
                    <td><label class="badge badge-gradient-success">${item.jenis}</label></td>
                    <td>${item.satuan}</td>
                    <td>Rp ${new Intl.NumberFormat('id-ID').format(item.harga_jual)}</td>
                    <td>${item.stok}</td>
                    <td>${item.supplier}</td>
                    <td>
                        <button class="btn btn-sm btn-warning"><i class="mdi mdi-pencil"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
                    </td>
                </tr>
            `;
            tableBody.append(rowHtml);
        });
    }

    // Fungsi untuk merender paginasi (menggunakan data dari Laravel)
    function renderPagination(paginator) {
        const pagination = $('#pagination');
        pagination.empty();
        if (!paginator.links || paginator.links.length <= 3) return; // Jika hanya ada prev, current, next
        
        paginator.links.forEach(link => {
            // Ganti &laquo; dan &raquo; dengan teks
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

    // --- EVENT LISTENERS ---

    // Filter
    $('#searchInput, #filterJenis, #filterSupplier').on('keyup change', function() {
        fetchData(1);
    });

    // Paginasi
    $('#pagination').on('click', 'a', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            fetchData(page);
        }
    });

    // Submit form tambah data
    $('#addDataForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize(); // Ambil data form

        $.ajax({
            url: STORE_URL,
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Penting untuk keamanan Laravel
            },
            success: function(response) {
                if(response.success) {
                    $('#dataModal').modal('hide');
                    $('#addDataForm')[0].reset();
                    fetchData(1); // Muat ulang data ke halaman pertama
                    // Tampilkan notifikasi sukses jika ada
                }
            },
            error: function(err) {
                console.error("Gagal menyimpan data:", err);
                // Tampilkan pesan error validasi jika ada
            }
        });
    });

    // --- INISIALISASI ---
    populateFilters();
    fetchData(); 
});
</script>
  </body>

</html>