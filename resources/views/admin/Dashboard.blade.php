@extends('layout.app')

@section('title', 'Dashboard Kehadiran Anggota')

@section('content')

@php
  // Data utama
  $total = 732;
  $hadir = 516;
  $tidakHadir = $total - $hadir;

  $persenHadir = round(($hadir / $total) * 100, 2);
  $persenTidak = round(($tidakHadir / $total) * 100, 2);
@endphp

<!-- Header Judul -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-0">
      LOMBA CERDAS CERMAT PERIODE 2025 - 2026
    </h5>
    <small class="text-success d-block mt-1">Checked every 3 Second</small>
  </div>
  <div class="d-flex align-items-center ms-3">
    <select class="form-select form-select-sm w-auto">
      <option>LOMBA CERDAS CERMAT</option>
    </select>
  </div>
</div>

<!-- Statistik Utama -->
<div class="row g-4 mb-4">
  <div class="col-md-4">
    <div class="card p-4 text-start shadow-sm h-100">
      <h6 class="mb-2 fw-semibold">TOTAL SELURUH ANGGOTA</h6>
      <h3 class="fw-bold mb-2">{{ $total }} Anggota</h3>
      <div class="text-muted mb-2">Persentase (100%)</div>
      <div class="progress" style="height:6px;">
        <div class="progress-bar bg-primary" style="width:100%"></div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card p-4 text-start shadow-sm h-100">
      <h6 class="mb-2 fw-semibold">TOTAL ANGGOTA HADIR</h6>
      <h3 class="fw-bold text-success mb-2">{{ $hadir }} Anggota</h3>
      <div class="text-muted mb-2">Persentase ({{ $persenHadir }}%)</div>
      <div class="progress" style="height:6px;">
        <div class="progress-bar bg-success" style="width: {{ $persenHadir }}%"></div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card p-4 text-start shadow-sm h-100">
      <h6 class="mb-2 fw-semibold">TOTAL ANGGOTA TIDAK HADIR</h6>
      <h3 class="fw-bold text-danger mb-2">{{ $tidakHadir }} Anggota</h3>
      <div class="text-muted mb-2">Persentase ({{ $persenTidak }}%)</div>
      <div class="progress" style="height:6px;">
        <div class="progress-bar bg-danger" style="width: {{ $persenTidak }}%"></div>
      </div>
    </div>
  </div>
</div>

<!-- Daftar Per Sekolah -->
<div class="row g-4 mb-4">
  @php
    $sekolah = [
      ['img' => 'taruna.png', 'nama' => 'SMK TARUNA BHAKTI', 'total' => 82, 'hadir' => 82, 'tidak' => 28, 'persen' => 74.55],
      ['img' => 'sman2.png', 'nama' => 'SMAN 2 KOTA DEPOK', 'total' => 46, 'hadir' => 46, 'tidak' => 40, 'persen' => 53.49],
      ['img' => 'sman3.png', 'nama' => 'SMAN 3 KOTA DEPOK', 'total' => 77, 'hadir' => 77, 'tidak' => 25, 'persen' => 75.49],
      ['img' => 'man1.png', 'nama' => 'MAN 1 BOGOR', 'total' => 47, 'hadir' => 47, 'tidak' => 22, 'persen' => 68.12],
      ['img' => 'sman2.png', 'nama' => 'SMAN 2 KOTA DEPOK', 'total' => 37, 'hadir' => 37, 'tidak' => 31, 'persen' => 54.41],
      ['img' => 'sman2.png', 'nama' => 'SMAN 2 KOTA DEPOK', 'total' => 28, 'hadir' => 28, 'tidak' => 16, 'persen' => 63.64],
      ['img' => 'sman2.png', 'nama' => 'SMAN 2 KOTA DEPOK', 'total' => 36, 'hadir' => 36, 'tidak' => 17, 'persen' => 67.92],
      ['img' => 'sman2.png', 'nama' => 'SMAN 2 KOTA DEPOK', 'total' => 38, 'hadir' => 38, 'tidak' => 10, 'persen' => 79.17],
      ['img' => 'sman2.png', 'nama' => 'SMAN 2 KOTA DEPOK', 'total' => 125, 'hadir' => 125, 'tidak' => 27, 'persen' => 82.24],
    ];
  @endphp

  @foreach($sekolah as $s)
  <div class="col-sm-6 col-lg-3">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <!-- Logo + Info -->
        <div class="d-flex align-items-center mb-2">
          <img src="{{ asset('images/' . $s['img']) }}" alt="{{ $s['nama'] }}" 
               class="me-2 rounded" style="width:40px; height:40px; object-fit:contain;">
          <div>
            <div class="fw-bold">{{ $s['total'] }} Anggota 
              <span class="text-success">({{ $s['persen'] }}%)</span>
            </div>
            <div class="text-muted small">{{ $s['nama'] }}</div>
          </div>
        </div>

        <!-- Garis Pemisah -->
        <hr class="my-2">

        <!-- Hadir & Tidak Hadir -->
        <div class="d-flex text-center">
          <div class="flex-fill border-end">
            <div class="fw-bold">Hadir</div>
            <div class="text-success">{{ $s['hadir'] }}</div>
          </div>
          <div class="flex-fill">
            <div class="fw-bold">Tidak Hadir</div>
            <div class="text-danger">{{ $s['tidak'] }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endforeach
</div>

<!-- Tabel Daftar Anggota -->
<div class="card shadow-sm mt-4">
  <div class="card-header fw-bold">Daftar Kehadiran Anggota</div>
  <div class="card-body">
    <table id="anggotaTable" class="table table-bordered table-striped" style="width:100%">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Nama Anggota</th>
          <th>Nama Sekolah</th>
          <th>TIM</th>
          <th>Wilayah</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>1</td><td>Andi Saputra</td><td>SMK TARUNA BHAKTI</td><td>TIM A</td><td>DEPOK</td></tr>
        <tr><td>2</td><td>Budi Santoso</td><td>SMAN 2 KOTA DEPOK</td><td>TIM B</td><td>DEPOK</td></tr>
        <tr><td>3</td><td>Siti Aminah</td><td>SMAN 3 KOTA DEPOK</td><td>TIM C</td><td>DEPOK</td></tr>
        <tr><td>4</td><td>Aminah Aisyah</td><td>SMAN 2 KOTA DEPOK</td><td>TIM C</td><td>DEPOK</td></tr>
        <tr><td>5</td><td>Amenna Bunga</td><td>SMA 2 KOTA DEPOK</td><td>TIM A</td><td>DEPOK</td></tr>
        <tr><td>6</td><td>Sania Fitri</td><td>MAN 1 BOGOR</td><td>TIM C</td><td>BOGOR</td></tr>
        <tr><td>7</td><td>Even Ariel</td><td>SMAN Taruna Bhkati</td><td>TIM B</td><td>DEPOK</td></tr>
        <tr><td>8</td><td>Safitri</td><td>SMAN 2 KOTA DEPOK</td><td>TIM C</td><td>DEPOK</td></tr>
        <tr><td>9</td><td>Sena Fitri</td><td>SMK Taruna Bhakti</td><td>TIM E</td><td>DEPOK</td></tr>
      </tbody>
    </table>
  </div>
</div>

@endsection

@push('scripts')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- jQuery + DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
  $(document).ready(function() {
    $('#anggotaTable').DataTable({
      pageLength: 5,
      lengthMenu: [5, 10, 25, 50],
      language: {
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ data",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        paginate: {
          first: "Awal",
          last: "Akhir",
          next: "›",
          previous: "‹"
        }
      }
    });
  });
</script>
@endpush
