@extends('layout.userapp')

@section('content')
<!-- CSRF Token Meta -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .event-table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .badge-status {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .badge-hadir {
        background: #d4edda;
        color: #155724;
    }
    
    .badge-belum {
        background: #f8d7da;
        color: #721c24;
    }
    
    .btn-detail {
        background: #ffc107;
        color: #000;
        border: none;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-detail:hover {
        background: #ffb300;
        transform: translateY(-1px);
    }
    
    .btn-absen {
        background: #dc3545;
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-absen:hover {
        background: #c82333;
        transform: translateY(-1px);
    }
    
    .btn-absen:disabled {
        background: #6c757d;
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    .search-section {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .page-header {
        margin-bottom: 1.5rem;
    }

    /* Align table content to left */
    #eventTable thead th,
    #eventTable tbody td {
        text-align: left !important;
    }

    /* DataTables pagination alignment */
    .dataTables_wrapper .dataTables_paginate {
        text-align: left !important;
        padding-left: 0 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        margin: 0 2px;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 15px !important;
        border: none !important;
    }

    .event-info-box {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
    }

    .info-item {
        margin-bottom: 0.75rem;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-label {
        color: #6c757d;
        font-size: 13px;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-weight: 600;
        color: #333;
    }

    .token-value {
        color: #007bff !important;
        font-family: monospace;
        font-size: 15px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
    }

    .btn-submit {
        background: #007bff;
        color: white;
        padding: 0.75rem;
        border-radius: 8px;
        border: none;
        font-size: 15px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        background: #0056b3;
        transform: translateY(-1px);
        color: white;
    }
</style>

<div class="container-xl">
    <!-- Header -->
    <div class="page-header mb-4">
        <h2 class="page-title">Absensi Kehadiran</h2>
        <p class="text-muted">Daftar event yang dapat Anda ikuti</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search Section -->
    <div class="search-section">
        <div class="row align-items-end">
            <div class="col-md-12">
                <label class="form-label fw-semibold">Search</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Search ...">
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="event-table">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="eventTable">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="py-3">Nama Tim</th>
                        <th class="py-3">Sekolah</th>
                        <th class="py-3">Waktu Mulai</th>
                        <th class="py-3">Waktu Selesai</th>
                        <th class="py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $index => $event)
                        @php
                            $participant = $event->participants->first();
                            $hasAttended = $participant && $participant->attended_at;
                            $isActive = $event->is_attendance_active;
                            // Tombol absen aktif selama is_attendance_active = true
                            // Tidak peduli sudah lewat attendance_end atau belum
                            $canAttend = $isActive && !$hasAttended;
                        @endphp
                        <tr>
                            <td class="px-4">{{ $index + 1 }}</td>
                            <td>{{ $event->team->name }}</td>
                            <td>{{ $event->school->name }}</td>
                            <td>{{ $event->start_date->format('d-m-Y H:i') }}</td>
                            <td>{{ $event->end_date->format('d-m-Y H:i') }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($hasAttended)
                                        <span class="badge-status badge-hadir">
                                            <i class="bi bi-check-circle"></i> Sudah Absen
                                        </span>
                                    @else
                                        <button 
                                            class="btn-absen" 
                                            @if(!$canAttend) disabled @endif
                                            onclick="showAbsenModal({{ $event->id }}, '{{ $event->attendance_token }}', '{{ $event->start_date->format('d-m-Y H:i') }}', '{{ $event->end_date->format('d-m-Y H:i') }}')"
                                        >
                                            Absen
                                        </button>
                                    @endif
                                    <button class="btn-detail" onclick="showDetailModal({{ $event->id }})">
                                        Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2 mb-0">Tidak ada event yang tersedia</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Showing entries info -->
    @if($events->count() > 0)
        <div class="mt-3">
            <div class="text-muted small">
                Menampilkan {{ $events->count() }} event
            </div>
        </div>
    @endif
</div>

<!-- Modal Absen -->
<div class="modal fade" id="absenModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0" style="padding: 1.5rem 1.5rem 0.5rem;">
                <h5 class="modal-title fw-bold">Absensi Kehadiran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 1rem 1.5rem 1.5rem;">
                <form id="absenForm" action="{{ route('user.absensi.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="event_id" id="eventId">
                    
                    <!-- Info Event -->
                    <div class="event-info-box mb-3">
                        <div class="info-item">
                            <div class="info-label">Waktu Mulai</div>
                            <div class="info-value" id="eventStartTime">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Waktu Selesai</div>
                            <div class="info-value" id="eventEndTime">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Token</div>
                            <div class="info-value token-value" id="eventToken">-</div>
                        </div>
                    </div>

                    <!-- Validasi Token -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #333;">Validasi Token</label>
                        <input 
                            type="text" 
                            name="token" 
                            id="tokenInput"
                            class="form-control" 
                            placeholder="masukan token..." 
                            required
                            style="padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 8px;"
                        >
                    </div>

                    <!-- Status Kehadiran (Opsional) -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="color: #333;">Status Kehadiran</label>
                        <select 
                            name="status" 
                            class="form-select"
                            style="padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 8px;"
                        >
                            <option value="">-- Pilih jika berhalangan hadir --</option>
                            <option value="izin">Izin</option>
                        </select>
                        <small class="text-muted">Opsional - Kosongkan jika hadir, sistem akan otomatis mencatat kehadiran</small>
                    </div>

                    <button type="submit" class="btn btn-submit w-100">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0" style="padding: 1.5rem 1.5rem 0.5rem;">
                <h5 class="modal-title fw-bold">Detail Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 1rem 1.5rem 1.5rem;">
                <div id="detailContent">
                    <!-- Content will be loaded via JavaScript -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .detail-box {
        background: #ffffff;
        border-radius: 8px;
        padding: 0;
    }

    .detail-label {
        font-size: 13px;
        color: #6c757d;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .detail-value {
        background: #e3f2fd;
        color: #1976d2;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        word-break: break-word;
    }

    .status-section {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
    }

    .status-label {
        font-size: 13px;
        color: #6c757d;
        margin-bottom: 0.5rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-label::before {
        content: '';
        width: 8px;
        height: 8px;
        background: currentColor;
        border-radius: 50%;
    }

    .status-value {
        font-size: 15px;
        font-weight: 600;
    }

    .status-hadir-text {
        color: #10b981;
    }

    .status-terlambat-text {
        color: #f59e0b;
    }

    .status-izin-text {
        color: #8b5cf6;
    }

    .status-tidak-hadir-text {
        color: #ef4444;
    }

    .status-belum-absen-text {
        color: #6b7280;
    }

    .detail-loading {
        text-align: center;
        padding: 2rem;
    }

    .detail-error {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 8px;
        padding: 1rem;
        color: #856404;
        text-align: center;
    }
</style>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
// Setup CSRF token untuk semua AJAX request
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    // Initialize DataTable
    var table = $('#eventTable').DataTable({
        searching: true,
        paging: true,
        info: false,
        lengthChange: false,
        pageLength: 10,
        dom: 'rtip',
        language: {
            emptyTable: "Tidak ada event yang tersedia",
            zeroRecords: "Tidak ditemukan event yang sesuai",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        columnDefs: [
            { targets: '_all', className: 'text-start' },
            { targets: 0, orderable: false } // Disable sorting on No column
        ],
        // Re-draw row numbers on each draw (for pagination)
        fnDrawCallback: function() {
            var api = this.api();
            var startIndex = api.context[0]._iDisplayStart;
            api.column(0, {page: 'current'}).nodes().each(function(cell, i) {
                cell.innerHTML = startIndex + i + 1;
            });
        }
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Auto dismiss alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Refresh CSRF token setiap modal dibuka (prevent 419 error)
    $('#absenModal').on('show.bs.modal', function() {
        // Ambil CSRF token terbaru dari meta tag
        var token = $('meta[name="csrf-token"]').attr('content');
        $('input[name="_token"]').val(token);
    });

    // Handle form submit dengan validasi
    $('#absenForm').on('submit', function(e) {
        var token = $('#tokenInput').val().trim();
        
        if (!token) {
            e.preventDefault();
            alert('Token absensi harus diisi!');
            return false;
        }
        
        // Form akan submit normal dengan CSRF token yang fresh
        return true;
    });
});

function showAbsenModal(eventId, token, startTime, endTime) {
    $('#eventId').val(eventId);
    $('#eventToken').text(token);
    $('#eventStartTime').text(startTime);
    $('#eventEndTime').text(endTime);
    $('#tokenInput').val(''); // Clear previous input
    $('select[name="status"]').val(''); // Reset status dropdown
    $('#absenModal').modal('show');
}

function showDetailModal(eventId) {
    $('#detailModal').modal('show');
    
    // Load detail via AJAX
    $.ajax({
        url: `/user/absensi/${eventId}/detail`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                
                // Tentukan status class dan text
                let statusClass = '';
                let statusText = '';
                switch(data.status) {
                    case 'hadir':
                        statusClass = 'status-hadir-text';
                        statusText = 'Hadir';
                        break;
                    case 'terlambat':
                        statusClass = 'status-terlambat-text';
                        statusText = 'Terlambat';
                        break;
                    case 'izin':
                        statusClass = 'status-izin-text';
                        statusText = 'Izin';
                        break;
                    case 'belum_absen':
                        statusClass = 'status-belum-absen-text';
                        statusText = 'Belum Absen';
                        break;
                    case 'tidak_hadir':
                        statusClass = 'status-tidak-hadir-text';
                        statusText = 'Tidak Hadir';
                        break;
                    default:
                        statusClass = 'status-belum-absen-text';
                        statusText = 'Belum Absen';
                }
                
                let html = `
                    <div class="detail-grid">
                        <div class="detail-box">
                            <div class="detail-label">Nama</div>
                            <div class="detail-value">${data.event_name || '-'}</div>
                        </div>
                        
                        <div class="detail-box">
                            <div class="detail-label">Waktu Mulai</div>
                            <div class="detail-value">${data.start_time || '-'}</div>
                        </div>
                        
                        <div class="detail-box">
                            <div class="detail-label">Token</div>
                            <div class="detail-value">${data.token || '-'}</div>
                        </div>
                        
                        <div class="detail-box">
                            <div class="detail-label">Waktu Selesai</div>
                            <div class="detail-value">${data.end_time || '-'}</div>
                        </div>
                    </div>
                    
                    <div class="status-section">
                        <div class="status-label ${statusClass}">Status</div>
                        <div class="status-value ${statusClass}">
                            ${statusText}${data.attended_time ? ' · absen pukul ' + data.attended_time : ''}
                        </div>
                    </div>
                `;
                
                $('#detailContent').html(html);
            } else {
                $('#detailContent').html(`
                    <div class="detail-error">
                        <i class="bi bi-exclamation-triangle"></i>
                        <p class="mb-0 mt-2">${response.message || 'Data tidak ditemukan'}</p>
                    </div>
                `);
            }
        },
        error: function() {
            $('#detailContent').html(`
                <div class="detail-error">
                    <i class="bi bi-exclamation-triangle"></i>
                    <p class="mb-0 mt-2">Gagal memuat detail. Silakan coba lagi.</p>
                </div>
            `);
        }
    });
}
</script>

@endsection