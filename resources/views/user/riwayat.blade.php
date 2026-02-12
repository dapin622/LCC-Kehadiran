@extends('layout.userapp')

@section('title', 'Riwayat Absensi')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }
    
    .page-header h2 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .page-header p {
        margin: 0;
        opacity: 0.95;
    }
    
    .riwayat-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    
    .riwayat-card h5 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1f2937;
    }
    
    .absensi-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid #3b82f6;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .absensi-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .absensi-item.status-hadir {
        border-left-color: #10b981;
    }
    
    .absensi-item.status-terlambat {
        border-left-color: #f59e0b;
    }
    
    .absensi-item.status-tidak_hadir {
        border-left-color: #ef4444;
    }
    
    .absensi-item.status-izin {
        border-left-color: #8b5cf6;
    }
    
    .absensi-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }
    
    .absensi-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }
    
    .absensi-date {
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .status-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .status-badge.hadir {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-badge.terlambat {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-badge.tidak_hadir {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .status-badge.izin {
        background: #ede9fe;
        color: #5b21b6;
    }
    
    .absensi-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #4b5563;
        font-size: 0.9rem;
    }
    
    .detail-item i {
        color: #3b82f6;
    }
    
    .no-data {
        text-align: center;
        padding: 3rem 2rem;
    }
    
    .no-data-icon {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }
    
    .no-data h5 {
        color: #6b7280;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    
    .no-data p {
        color: #9ca3af;
        margin-bottom: 1.5rem;
    }
    
    .btn-absensi {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: transform 0.2s;
    }
    
    .btn-absensi:hover {
        transform: translateY(-2px);
        color: white;
    }
    
    .pagination {
        margin-top: 2rem;
        justify-content: center;
    }
    
    .pagination .page-link {
        color: #3b82f6;
        border-radius: 6px;
        margin: 0 0.25rem;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
    }
    
    @media (max-width: 768px) {
        .absensi-header {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .absensi-details {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid">
    @if(isset($error))
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ $error }}
        </div>
    @endif

    <!-- Page Header -->
    <div class="page-header">
        <h2>Riwayat Absensi</h2>
        <p>Lihat semua riwayat kehadiran Anda</p>
    </div>

    <!-- Riwayat Card -->
    <div class="riwayat-card">
        <h5>
            <i class="bi bi-clock-history me-2"></i>
            Daftar Kehadiran
        </h5>
        
        @if($attendances && $attendances->count() > 0)
            @foreach($attendances as $attendance)
               @php
                        $statusClass = '';
                        $statusText = '';

                        $event = $attendance->event;

                        if ($attendance->status === 'izin') {
                            $statusClass = 'izin';
                            $statusText = 'Izin';
                        }

                        elseif ($attendance->status === 'tidak_hadir') {
                            $statusClass = 'tidak_hadir';
                            $statusText = 'Tidak Hadir';
                        }

                        elseif ($attendance->attended_at && $event && $event->attendance_end) {

                            if ($attendance->attended_at->greaterThan($event->attendance_end)) {
                                $statusClass = 'terlambat';
                                $statusText = 'Terlambat';
                            } else {
                                $statusClass = 'hadir';
                                $statusText = 'Hadir';
                            }

                        }

                        else {
                            $statusClass = 'tidak_hadir';
                            $statusText = 'Tidak Hadir';
                        }
                    @endphp


                
                <div class="absensi-item status-{{ $statusClass }}">
                    <div class="absensi-header">
                        <div>
                            <div class="absensi-title">
                                {{ $attendance->event->name ?? 'Event' }}
                            </div>
                            <div class="absensi-date">
                                <i class="bi bi-calendar3"></i>
                                @if($attendance->attended_at)
                                    {{ $attendance->attended_at->format('d F Y, H:i') }} WIB
                                @else
                                    <span class="text-muted">Waktu tidak tersedia</span>
                                @endif
                            </div>
                        </div>
                        <span class="status-badge {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </div>
                    
                    <div class="absensi-details">
                        <div class="detail-item">
                            <i class="bi bi-people-fill"></i>
                            <span>TIM: {{ optional($attendance->event->team)->name ?? '-' }}</span>
                        </div>
                        
                        <div class="detail-item">
                            <i class="bi bi-building"></i>
                            <span>{{ optional($attendance->event->school)->name ?? optional($member->school)->name ?? '-' }}</span>
                        </div>

                        @if($attendance->event && $attendance->event->description)
                        <div class="detail-item detail-description"
                            title="{{ $attendance->event->description }}">
                            <i class="bi bi-card-text"></i>
                            <span>
                                {{ \Illuminate\Support\Str::limit($attendance->event->description, 120, '...') }}
                            </span>
                        </div>
                        @endif

                        <!-- <div class="detail-item">
                            <i class="bi bi-key-fill"></i>
                            <span>Token: {{ $attendance->event->attendance_token ?? '-' }}</span>
                        </div> -->
                        
                        @if($attendance->event && $attendance->event->attendance_start)
                        <div class="detail-item">
                            <i class="bi bi-clock"></i>
                            <span>Mulai: {{ $attendance->event->attendance_start->format('H:i') }}</span>
                        </div>
                        @endif
                        
                        @if($attendance->event && $attendance->event->attendance_end)
                        <div class="detail-item">
                            <i class="bi bi-clock-fill"></i>
                            <span>Selesai: {{ $attendance->event->attendance_end->format('H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $attendances->links() }}
            </div>
        @else
            <div class="no-data">
                <div class="no-data-icon">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <h5>Belum Ada Riwayat Absensi</h5>
                <p>Anda belum melakukan absensi. Silakan lakukan absensi terlebih dahulu.</p>
                <a href="{{ route('user.absensi') }}" class="btn-absensi">
                    <i class="bi bi-calendar-check"></i>
                    Lakukan Absensi
                </a>
            </div>
        @endif
    </div>
</div>
@endsection