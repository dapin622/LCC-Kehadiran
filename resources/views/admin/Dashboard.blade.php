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
  @foreach($schools as $school)
  @php
    $total = $school->members_count;
    $hadir = $total; 
    $tidak = 0; 
    $persen = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;
    $img = $school->photo ?? 'default.png'; 
  @endphp
  <div class="col-sm-6 col-lg-3">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          @if (!empty($school->photo) && file_exists(public_path('uploads/foto/' . $school->photo)))
              <img src="{{ asset('uploads/foto/' . $school->photo) }}" 
                  alt="{{ $school->name }}" 
                  class="me-2 rounded" 
                  style="width:60px; height:60px; object-fit:contain;">
          @else
              <div class="d-flex justify-content-center align-items-center rounded me-2" 
                  style="width:60px; height:60px;">
                  <i class="bi bi-buildings fs-2 text-secondary"></i>
              </div>
          @endif
          <div>
            <div class="fw-bold">{{ $total }} Anggota 
              <span class="text-success">({{ $persen }}%)</span>
            </div>
            <div class="text-muted small">{{ $school->name }}</div>
          </div>
        </div>
        <hr class="my-2">
        <div class="d-flex text-center">
          <div class="flex-fill border-end">
            <div class="fw-bold">Hadir</div>
            <div class="text-success">{{ $hadir }}</div>
          </div>
          <div class="flex-fill">
            <div class="fw-bold">Tidak Hadir</div>
            <div class="text-danger">{{ $tidak }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endforeach
</div>

<!-- Tabel Daftar Anggota -->
<div class="card shadow-sm mt-4">
  <div class="card-header fw-bold px-3 pt-3 d-flex" style="background-color: white;">Daftar Kehadiran Anggota</div>
  <div class="card-body">
  <table id="anggotaTable" class="table table-bordered table-striped" style="width:100%">
    <thead class="table-light">
      <tr>
        <th>No</th>
        <th>Nama Anggota</th>
        <th>Nama Sekolah</th>
        <th>Kelas</th>
        <th>Wilayah</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
</div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
  $('#anggotaTable').DataTable({
    ajax: "{{ route('admin.dashboard.member') }}",
    columns: [
      { 
        data: null, 
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
        className: 'text-center'
      },
      { data: 'name' },
      { data: 'school.name', defaultContent: '-' },
      { data: 'class.name', defaultContent: '-' },
      { data: 'school.region', defaultContent: '-' }
    ],
    pageLength: 5,
    lengthMenu: [5, 10, 25, 50],
    language: {
      // search: "Cari:",
      // lengthMenu: "Tampilkan _MENU_ data",
      // info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
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

