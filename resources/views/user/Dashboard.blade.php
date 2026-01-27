@extends('layout.userapp')

@section('title', 'Dashboard Siswa')

@section('content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .dashboard-header h2 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }
    
    .dashboard-header p {
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        opacity: 0.95;
        position: relative;
        z-index: 1;
    }
    
    .date-time {
        display: flex;
        gap: 2rem;
        font-size: 0.95rem;
        position: relative;
        z-index: 1;
    }
    
    .date-time > div {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }
    
    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .stat-icon.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .stat-icon.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .stat-icon.warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    
    .stat-icon.info {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }
    
    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1;
    }
    
    .stat-label {
        color: #6b7280;
        font-size: 0.9rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }
    
    .quick-actions {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-top: 2rem;
    }
    
    .quick-actions h5 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1f2937;
    }
    
    .action-btn {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        text-decoration: none;
        color: #1f2937;
        font-weight: 600;
        transition: all 0.2s;
        margin-bottom: 1rem;
    }
    
    .action-btn:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
        transform: translateX(5px);
    }
    
    .action-btn i {
        font-size: 1.5rem;
    }
    
    .info-card {
        background: linear-gradient(135deg, #e0e7ff 0%, #ddd6fe 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 2rem;
    }
    
    .info-card h6 {
        font-weight: 700;
        color: #4c1d95;
        margin-bottom: 0.5rem;
    }
    
    .info-card p {
        color: #5b21b6;
        margin: 0;
        font-size: 0.9rem;
    }
    
    @media (max-width: 768px) {
        .dashboard-header h2 {
            font-size: 1.5rem;
        }
        
        .dashboard-header {
            padding: 1.5rem;
        }
        
        .date-time {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .stat-value {
            font-size: 2rem;
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

    <!-- Header Dashboard -->
    <div class="dashboard-header">
        <div class="row">
            <div class="col-12">
                <h2>Dashboard Siswa</h2>
                <p>Selamat datang, {{ $member ? $member->name : Auth::user()->name }}</p>
                
                <div class="date-time">
                    <div>
                        <i class="bi bi-calendar3"></i>
                        <span id="currentDate"></span>
                    </div>
                    <div>
                        <i class="bi bi-clock"></i>
                        <span id="currentTime"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row g-3">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon primary">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalAbsensi }}</div>
                <div class="stat-label">Total Absensi</div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalHadir }}</div>
                <div class="stat-label">Total Hadir</div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon warning">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalTerlambat }}</div>
                <div class="stat-label">Total Terlambat</div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon info">
                        <i class="bi bi-envelope-paper"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalIzin }}</div>
                <div class="stat-label">Total Izin</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h5>
            <i class="bi bi-lightning-charge-fill me-2"></i>
            Aksi Cepat
        </h5>
        
        <a href="{{ route('user.absensi') }}" class="action-btn">
            <i class="bi bi-calendar-check"></i>
            <div>
                <div style="font-size: 1.05rem;">Lakukan Absensi</div>
                <small style="color: #6b7280; font-weight: 400;">Input kehadiran Anda sekarang</small>
            </div>
        </a>

        <a href="{{ route('user.riwayat') }}" class="action-btn">
            <i class="bi bi-clock-history"></i>
            <div>
                <div style="font-size: 1.05rem;">Lihat Riwayat Absensi</div>
                <small style="color: #6b7280; font-weight: 400;">Cek semua riwayat kehadiran Anda</small>
            </div>
        </a>
    </div>

    <!-- Info Card -->
    <div class="info-card">
        <h6>
            <i class="bi bi-info-circle-fill me-2"></i>
            Informasi
        </h6>
        <p>
            Pastikan Anda melakukan absensi tepat waktu sesuai jadwal yang ditentukan. 
            Jika ada kendala, silakan hubungi admin atau pengurus.
        </p>
    </div>
</div>

@push('scripts')
<script>
    function updateDateTime() {
        const now = new Date();
        
        // Format tanggal
        const options = { day: '2-digit', month: 'short', year: 'numeric' };
        const dateStr = now.toLocaleDateString('id-ID', options);
        document.getElementById('currentDate').textContent = dateStr;
        
        // Format waktu
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        document.getElementById('currentTime').textContent = `${hours}:${minutes} WIB`;
    }
    
    // Update setiap detik
    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>
@endpush
@endsection