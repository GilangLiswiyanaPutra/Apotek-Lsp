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
        <a class="sidebar-brand brand-logo" href="{{ route('dashboard') }}"><img src="{{ asset('images/logo.svg') }}" alt="logo" /></a>
        <a class="sidebar-brand brand-logo-mini" href="{{ route('dashboard') }}"><img src="{{ asset('images/logo-mini.svg') }}" alt="logo" /></a>
    </div>
    <ul class="nav">
        @auth
        <li class="nav-item profile">
            <div class="profile-desc">
                <div class="profile-pic">
                    <div class="count-indicator">
                        <img class="img-xs rounded-circle " src="{{ asset('images/faces/face15.jpg') }}" alt="">
                        <span class="count bg-success"></span>
                    </div>
                    <div class="profile-name">
                        <h5 class="mb-0 font-weight-normal">{{ Auth::user()->name }}</h5>
                        <span>{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                </div>
            </div>
        </li>
        @endauth

        <li class="nav-item nav-category"><span class="nav-link">Navigation</span></li>
        
        {{-- [FIX] Menambahkan class 'active' secara dinamis --}}
        <li class="nav-item menu-items {{ Request::is('dashboard*') || Request::is('/') || Request::routeIs('index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="menu-icon"><i class="mdi mdi-cash-register"></i></span>
                <span class="menu-title">Beranda</span>
            </a>
        </li>
        
        {{-- Tampilkan menu ini hanya untuk Admin & Apoteker --}}
        @if(Auth::user()->role == 'admin')
            <li class="nav-item menu-items {{ Request::is('apotekers*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('apotekers.index') }}">
                    <span class="menu-icon"><i class="mdi mdi-account-circle"></i></span>
                    <span class="menu-title">Data Apoteker</span>
                </a>
            </li>
            <li class="nav-item menu-items {{ Request::is('suppliers*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('suppliers.index') }}">
                    <span class="menu-icon"><i class="mdi mdi-ambulance"></i></span>
                    <span class="menu-title">Data Supplier</span>
                </a>
            </li>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'apoteker']))
            <li class="nav-item menu-items {{ Request::is('obats*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('obats.index') }}">
                    <span class="menu-icon"><i class="mdi mdi-pill"></i></span>
                    <span class="menu-title">Data Obat</span>
                </a>
            </li>
             <li class="nav-item menu-items {{ Request::is('pelanggans*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pelanggans.index') }}">
                    <span class="menu-icon"><i class="mdi mdi-account-multiple"></i></span>
                    <span class="menu-title">Data Pelanggan</span>
                </a>
            </li>
            <li class="nav-item menu-items {{ Request::is('pembelian*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pembelians.index') }}">
                    <span class="menu-icon"><i class="mdi mdi-cart-outline"></i></span>
                    <span class="menu-title">Pembelian</span>
                </a>
            </li>
            @endif
            @if(in_array(Auth::user()->role, ['admin', 'apoteker', 'pelanggan']))
            {{-- Transaksi Penjualan sekarang adalah halaman utama (dashboard) --}}
            <li class="nav-item menu-items {{ Request::is('riwayat-penjualan') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('penjualans.riwayat') }}">
                    <span class="menu-icon"><i class="mdi mdi-history"></i></span>
                    <span class="menu-title">Riwayat Penjualan</span>
                </a>
            </li>
        @endif
        
         <li class="nav-item menu-items">
            <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="menu-icon"><i class="mdi mdi-logout text-danger"></i></span>
                <span class="menu-title">Logout</span>
            </a>
        </li>
    </ul>
</nav>

{{-- Form logout tersembunyi --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>