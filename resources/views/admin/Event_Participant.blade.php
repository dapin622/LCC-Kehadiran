@extends('layout.app')

@section('content')


    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                     <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('admin.event') }}" 
                            class="btn btn-icon btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i>
                        </a>

                        <h4 class="page-title mb-0">
                            Participants Event
                        </h4>
                    </div>

                    <div class="page-pretitle mt-1" style="margin-left: 38px;">
                        <small class="text-muted">
                            {{ optional($event->team)->name }} - {{ optional($event->school)->name }}
                        </small>
                    </div>

                </div>

                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <!-- <a href="#" class="btn btn-success d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-excel"
                                 width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                 fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2"/>
                                <path d="M10 12l4 5"/>
                                <path d="M10 17l4 -5"/>
                            </svg>
                            Export
                        </a> -->
                     <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5v14"/>
                                <path d="M5 12h14"/>
                            </svg>
                            Add event
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5v14"/>
                                <path d="M5 12h14"/>
                            </svg>
                            Add Tim
                        </button> -->
                    </div>
                </div>
            </div>
        </div><br>

    @if(session('success'))
            <div class="alert alert-success" id="sessionAlert">{{ session('success') }}</div>
        @endif

   <div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
              <div id="alertSuccess" class="alert alert-success d-none"></div>
                <div class="card">

                    <!-- Bagian Search & Filter -->
                    <div class="card-header px-4 pt-4 d-flex" style="background-color: white;">
                        <div class="w-100 me-2">
                            <div class="form-label">Search</div>
                            <input type="text" id="globalSearch" class="form-control" placeholder="Search sekolah..."/>
                        </div>
                        <div class="w-100 me-2">
                            <div class="form-label">Hadir</div>
                            <select id="filterHadir" class="form-select">
                              
                            </select>
                        </div>
                        
                        <div class="w-100">
                            <div class="form-label">Tidak Hadir</div>
                            <select id="filterTidakHadir" class="form-select">
                                
                            </select>
                        </div>
                    </div>

                        <div class="table-responsive p-2">
                        <table id="participantTable" class="table card-table table-vcenter text-nowrap datatable">
                                 <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <!-- <th>Tim</th>
                                    <th>Sekolah</th> -->
                                    <th>Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($members as $i => $member)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $member->name }}</td>
                                <td>
                                    @if($member->participants->isNotEmpty())
                                        @php
                                            $participant = $member->participants->first();
                                            $status = $participant->attendance_status;
                                        @endphp

                                        @if($status === 'hadir')
                                            <span class="badge bg-success">Hadir</span>

                                        @elseif($status === 'terlambat')
                                            <span class="badge bg-warning text-dark">Terlambat</span>

                                        @elseif($status === 'izin')
                                            <span class="badge bg-info">Izin</span>

                                        @elseif($status === 'tidak_hadir')
                                            <span class="badge bg-danger">Tidak Hadir</span>
                                        @endif

                                        @if($participant->attended_at)
                                            <div class="text-muted small mt-1">
                                                Absen pukul {{ $participant->attended_at->format('H:i') }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Belum Absen</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>






<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>


<script>
$(document).ready(function(){

    var table = $('#participantTable').DataTable({
        searching: true,
        dom: 'lrtip',      
        lengthChange: true,
        paging: true,
        info: true
    });

    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

    $('#filterHadir').on('change', function () {
        table.column(2).search(this.value).draw();
    });

    $("#filterTidakHadir").on("change", function() {
        table.column(3).search(this.value).draw();
    });

    
});
</script>
@endsection
