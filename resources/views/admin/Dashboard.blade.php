@extends('layout.app')

@section('title', 'Dashboard Kehadiran Anggota')

@section('content')

@php
  // Data utama
  $persenHadir = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;
  $persenTidak = $total > 0 ? round(($tidakHadir / $total) * 100, 2) : 0;
@endphp

<!-- Header Judul -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="fw-bold mb-0">
      LOMBA CERDAS CERMAT
    </h2>
    <small class="text-muted">Data berdasarkan anggota yang sudah melakukan absensi</small>
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
    $hadir = $school->hadir_count ?? 0;
    $tidak = $school->tidak_hadir_count ?? 0;
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

<!-- Tabel Daftar Anggota (TANPA AJAX - VERSI SIMPLE) -->
<div class="card shadow-sm mt-4">
  <div class="card-header fw-bold px-3 pt-3 d-flex" style="background-color: white;">
    Daftar Anggota Yang Sudah Absen
  </div>
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
      <tbody>
        @php
          // Ambil member yang sudah absen
          $attendedMemberIds = \App\Models\EventParticipant::whereNotNull('attended_at')
              ->distinct()
              ->pluck('member_id');
          
          $attendedMembers = \App\Models\Member::with(['school', 'class'])
              ->whereIn('id', $attendedMemberIds)
              ->get();
        @endphp

        
          @foreach($attendedMembers as $index => $member)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $member->name }}</td>
            <td>{{ $member->school->name ?? '-' }}</td>
            <td>{{ $member->class->name ?? '-' }}</td>
            <td>{{ $member->school->region ?? '-' }}</td>
          </tr>
          @endforeach
      </tbody>
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
  // Initialize DataTable tanpa AJAX
  $('#anggotaTable').DataTable({
    pageLength: 5,
    lengthMenu: [5, 10, 25, 50],
    language: {
      paginate: {
        first: "Awal",
        last: "Akhir",
        next: "›",
        previous: "‹"
      },
      emptyTable: "Belum ada anggota yang melakukan absensi",
      zeroRecords: "Tidak ditemukan data yang sesuai"
    }
  });
});
</script>
@endpush