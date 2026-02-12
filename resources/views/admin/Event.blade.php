@extends('layout.app')

@section('content')
<style>
    .alert-danger ul {
        list-style-type: none; 
        padding-left: 0;       
        margin-bottom: 0;
    }
    .alert-danger li {
        margin-left: 0;
    }
    #eventTable td:nth-child(6) {
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
</style>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h4 class="page-title">
                        Event
                    </h4>
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
                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5v14"/>
                                <path d="M5 12h14"/>
                            </svg>
                            Add event
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5v14"/>
                                <path d="M5 12h14"/>
                            </svg>
                            Add Tim
                        </button>
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
                            <div class="form-label">Tim</div>
                            <select id="filterTeam" class="form-select">
                                <option value="">All</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->name }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="w-100">
                            <div class="form-label">Sekolah</div>
                            <select id="filterSchool" class="form-select">
                                <option value="">All</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->name }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                        <div class="table-responsive p-2">
                        <table id="eventTable" class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Tim</th>
                                        <th>Sekolah</th>
                                        <th>Waktu Mulai</th>
                                        <th>Waktu Selesai</th>
                                        <th>Keterangan</th>
                                        <th style="width:150px;">Aksi</th>
                                    </tr>
                                </thead>

                            <tbody>
                                 @foreach($events as $event)
                                <tr data-id="{{ $event->id }}" data-team-id="{{ $event->team_id }}">
                                    <td>{{ $event->id }}</td>
                                    <td>{{ $event->team->name }}</td>
                                    <td>{{ $event->school->name }}</td>
                                    <td>{{ $event->start_date->format('d-m-Y H:i') }}</td>
                                    <td>{{ $event->end_date->format('d-m-Y H:i') }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($event->description, 50, '...') }}</td>
                                    <td>
                                        <button 
                                            class="btn btn-warning btn-sm editBtn"
                                            data-id="{{ $event->id }}"
                                            data-team="{{ $event->team_id }}"
                                            data-school="{{ $event->school_id }}"
                                            data-start="{{ $event->start_date->format('Y-m-d\TH:i') }}"
                                            data-end="{{ $event->end_date->format('Y-m-d\TH:i') }}"
                                            data-description="{{ $event->description }}"
                                            data-token="{{ $event->attendance_token }}"
                                            data-absen-start="{{ optional($event->attendance_start)->format('Y-m-d\TH:i') }}"
                                            data-absen-end="{{ optional($event->attendance_end)->format('Y-m-d\TH:i') }}"
                                            data-active="{{ $event->is_attendance_active }}"
                                            >
                                            Edit
                                        </button>

                                       <form action="{{ route('admin.event.destroy', $event->id) }}" method="POST" style="display:inline-block;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin menghapus event ini?')">Delete</button>
                                        </form>
                                         <a href="{{ route('admin.event_participants', $event->id) }}"
                                            class="btn btn-info btn-sm me-1">
                                            <i class="bi bi-people"></i> Participant
                                        </a>

                                        <button 
                                            class="btn btn-success btn-sm absenBtn"
                                            data-id="{{ $event->id }}"
                                            data-name="{{ $event->name }}"
                                            data-team="{{ $event->team->name }}"
                                            data-school="{{ $event->school->name }}"
                                            data-start="{{ $event->start_date->format('d-m-Y H:i') }}"
                                            data-end="{{ $event->end_date->format('d-m-Y H:i') }}"
                                            data-description="{{ $event->description }}"
                                            data-token="{{ $event->attendance_token }}"
                                            data-absen-start="{{ optional($event->attendance_start)->format('Y-m-d\TH:i') }}"
                                            data-absen-end="{{ optional($event->attendance_end)->format('Y-m-d\TH:i') }}"
                                            data-active="{{ $event->is_attendance_active }}"
                                        >
                                            <i class="bi bi-info-circle"></i> Info Event
                                        </button>

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



<!-- ADD EVENT MODAL -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="addEventForm" action="{{ route('admin.event.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Tambah Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- ALERT ERROR (untuk AJAX atau validasi) -->
        <div id="alertErrorEvent" class="alert alert-danger d-none"></div>

        <div class="modal-body">

          {{-- VALIDATION ERROR --}}
          @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif

          <div class="mb-3">
            <label class="form-label">Tim</label>
            <select name="team_id" class="form-select" required>
                <option value="">-- Pilih Tim --</option>
                @foreach($teams as $team)
                <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach
            </select>
        </div>


          <div class="mb-3">
            <label class="form-label">Sekolah</label>
            <select name="school_id" class="form-select" required>
              <option value="">-- Pilih Sekolah --</option>
              @foreach($schools as $school)
                <option value="{{ $school->id }}">{{ $school->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Mulai</label>
            <input type="datetime-local" name="start_date" id="startDate"class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Selesai</label>
            <input type="datetime-local" name="end_date" id="endDate" class="form-control" required>
            <div class="invalid-feedback" id="endDateError"></div>

          </div>

        <div class="mb-3">
            <label class="form-label">Keterangan Event</label>
            <textarea name="description" class="form-control" rows="3"
                placeholder="Contoh: Babak penyisihan lomba cerdas cermat"></textarea>
        </div>


          <hr>
            <h5>Pengaturan Absensi</h5>

            <div class="mb-3">
                <label>Token Absensi</label>
                <input type="text" name="attendance_token" class="form-control"
                    placeholder="Contoh: EVT2026">
            </div>

            <!-- <div class="row">
                <div class="col-md-4">
                    <label>Latitude (Lintang)</label>
                    <input type="text" name="latitude" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Longitude (Bujur)</label>
                    <input type="text" name="longitude" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Radius (meter)</label>
                    <input type="number" name="radius" class="form-control">
                </div>
            </div> -->

            
                <div class="mb-3">
                    <label>Absensi Dibuka</label>
                    <input type="datetime-local" name="attendance_start" id="attendanceStart" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Absensi Ditutup</label>
                    <input type="datetime-local" name="attendance_end" id="attendanceEnd" class="form-control">
                    <div class="invalid-feedback" id="attendanceEndError"></div>
                </div>
           

            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox"
                    name="is_attendance_active" value="1">
                <label class="form-check-label">
                    Aktifkan Absensi
                </label>
            </div>


        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>

      </form>

    </div>
  </div>
</div>

<!--absen modal-->
<div class="modal fade" id="absenModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Detail Event & Absensi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <h6 class="fw-bold">Informasi Event</h6>
        <div class="mb-2">
            <strong>Tim:</strong>
            <div id="ViewTeam"></div>
        </div>
        <div class="mb-2">
            <strong>Sekolah:</strong>
            <div id="ViewSchool"></div>
        </div>
        <div class="mb-2">
            <strong>Waktu:</strong>
            <div id="ViewDateTime"></div>
        </div>
        <div class="mb-2">
            <strong>Keterangan:</strong>
            <div id="ViewDescription"></div>
        </div>

        <hr>

        <h6 class="fw-bold">Informasi Absensi</h6>
        <div class="mb-2">
            <strong>Token Absensi:</strong>
            <div id="viewToken"></div>
        </div>

        <!-- <div class="mb-2">
            <strong>Lokasi:</strong>
            <div id="viewLocation"></div>
        </div>

        <div class="mb-2">
            <strong>Radius:</strong>
            <div id="viewRadius"></div>
        </div> -->

        <div class="mb-2">
            <strong>Waktu Absensi:</strong>
            <div id="viewTime"></div>
        </div>

        <div class="mb-2">
            <strong>Status:</strong>
            <span id="viewStatus" class="badge"></span>
        </div>

      </div>

    </div>
  </div>
</div>


<!-- ADD TEAM MODAL -->
<div class="modal fade" id="addTeamModal" tabindex="-1">
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Kelola Data Tim</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="input-group mb-3">
          <input type="text" id="newTeamName" class="form-control" placeholder="Nama tim baru">
          <button id="addTeamBtn" class="btn btn-primary">Tambah</button>
        </div>

        <div id="alertSuccessTeam" class="alert alert-success d-none"></div>
        <div id="alertErrorTeam" class="alert alert-danger d-none"></div>

        <table id="teamTable" class="table table-bordered align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama Tim</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($teams as $team)
            <tr data-id="{{ $team->id }}">
              <td>{{ $team->id }}</td>
              <td>
                <input type="text" class="form-control form-control-sm team-name" value="{{ $team->name }}">
              </td>
              <td>
                <button class="btn btn-warning btn-sm btn-update-team">Update</button>
                <button class="btn btn-danger btn-sm btn-delete-team">Hapus</button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>


<!-- EDIT MODAL -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="editEventForm" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Edit Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- ALERT ERROR -->
        <div id="AlertErroreditEvent" class="alert alert-danger d-none"></div>

        <div class="modal-body">

          <input type="hidden" id="editEventId" name="id">

          <div class="mb-3">
            <label class="form-label">Tim</label>
            <select name="team_id" class="form-select" required>
                <option value="">-- Pilih Tim --</option>
                @foreach($teams as $team)
                <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach
            </select>
        </div>

          <div class="mb-3">
            <label class="form-label">Sekolah</label>
            <select name="school_id" id="editSchoolId" class="form-select" required>
              @foreach($schools as $school)
                <option value="{{ $school->id }}">{{ $school->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Mulai</label>
            <input type="datetime-local" name="start_date" id="editStartDate" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Selesai</label>
            <input type="datetime-local" name="end_date" id="editEndDate" class="form-control" required>
            <div class="invalid-feedback" id="editEndDateError"></div>

          </div>

        <div class="mb-3">
            <label class="form-label">Keterangan Event</label>
            <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
        </div>


           <hr>
            <h5>Pengaturan Absensi</h5>

            <div class="mb-3">
                <label>Token Absensi</label>
                <input type="text" name="attendance_token" id="editToken" class="form-control"
                    placeholder="Contoh: EVT2026">
            </div>

            <!-- <div class="row">
                <div class="col-md-4">
                    <label>Latitude (Lintang)</label>
                    <input type="text" name="latitude" id="editLat" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Longitude (Bujur)</label>
                    <input type="text" name="longitude" id="editLng" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Radius (meter)</label>
                    <input type="number" name="radius" id="editRadius" class="form-control">
                </div>
            </div> -->

            
                <div class="mb-3">
                    <label>Absensi Dibuka</label>
                    <input type="datetime-local" name="attendance_start" id="editAbsenStart" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Absensi Ditutup</label>
                    <input type="datetime-local" name="attendance_end" id="editAbsenEnd" class="form-control">
                    <div class="invalid-feedback" id="editAttendanceEndError"></div>
                </div>
           

            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox"
                    name="is_attendance_active" id="editActive" value="1">
                <label class="form-check-label">
                    Aktifkan Absensi
                </label>
            </div>


        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>

      </form>

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

    var table = $('#eventTable').DataTable({
        searching: true,
        dom: 'lrtip',      
        lengthChange: true,
        paging: true,
        info: true
    });

    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

    $('#filterTeam').on('change', function () {
        table.column(1).search(this.value).draw();
    });

    $("#filterSchool").on("change", function() {
        table.column(2).search(this.value).draw();
    });

    function formatTimeNoT(datetime) {
        if (!datetime) return '-';
        return datetime.replace('T', ' ');
    }

    setTimeout(function () {
        $('#sessionAlert').fadeOut(function () {
            $(this).remove();
        });
    }, 3000);

    function validateEventTime(startSelector, endSelector, errorSelector) {
        const start = $(startSelector).val();
        const end   = $(endSelector).val();

        if (!start || !end) return true;

        if (start === end) {
            $(endSelector).addClass('is-invalid');
            $(errorSelector).text('Waktu selesai tidak boleh sama dengan waktu mulai');
            return false;
        } else {
            $(endSelector).removeClass('is-invalid');
            $(errorSelector).text('');
            return true;
        }
    }

    $('#startDate, #endDate').on('change', function () {
        validateEventTime('#startDate', '#endDate', '#endDateError');
    });
    $('#editStartDate, #editEndDate').on('change', function () {
        validateEventTime(
            '#editStartDate',
            '#editEndDate',
            '#editEndDateError'
        );
    });

    function validateAttendanceTime(
        eventStartSel,
        eventEndSel,
        absenStartSel,
        absenEndSel,
        errorSel
    ) {
        const eventStart = $(eventStartSel).val();
        const eventEnd   = $(eventEndSel).val();
        const absenStart = $(absenStartSel).val();
        const absenEnd   = $(absenEndSel).val();

        if (!absenStart || !absenEnd) return true;

        if (absenStart === absenEnd) {
            $(absenEndSel).addClass('is-invalid');
            $(errorSel).text('Waktu absensi ditutup tidak boleh sama dengan waktu dibuka');
            return false;
        }

        if (absenStart > absenEnd) {
            $(absenEndSel).addClass('is-invalid');
            $(errorSel).text('Waktu absensi ditutup tidak boleh sebelum waktu dibuka');
            return false;
        }

        if (eventStart && absenStart < eventStart) {
            $(absenEndSel).addClass('is-invalid');
            $(errorSel).text('Waktu absensi tidak boleh sebelum event dimulai');
            return false;
        }

        if (eventEnd && absenEnd > eventEnd) {
            $(absenEndSel).addClass('is-invalid');
            $(errorSel).text('Waktu absensi tidak boleh melebihi waktu event');
            return false;
        }

        $(absenEndSel).removeClass('is-invalid');
        $(errorSel).text('');
        return true;
    }
    $('#attendanceStart, #attendanceEnd, #startDate, #endDate').on('change', function () {
        validateAttendanceTime(
            '#startDate',
            '#endDate',
            '#attendanceStart',
            '#attendanceEnd',
            '#attendanceEndError'
        );
    });
    $('#editAbsenStart, #editAbsenEnd, #editStartDate, #editEndDate').on('change', function () {
        validateAttendanceTime(
            '#editStartDate',
            '#editEndDate',
            '#editAbsenStart',
            '#editAbsenEnd',
            '#editAttendanceEndError'
        );
    });




    // ADD EVENT
   $('#addEventForm').submit(function(e){
    e.preventDefault();
    if (!validateEventTime('#startDate', '#endDate', '#endDateError')) {
        return;
    }
    if (!validateAttendanceTime(
        '#startDate',
        '#endDate',
        '#attendanceStart',
        '#attendanceEnd',
        '#attendanceEndError'
    )) {
        return;
    }


    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
       success: function(res){
                $('#addEventModal').modal('hide');
                $('#alertErrorEvent').addClass('d-none').html('');

                $('#alertSuccess')
                    .removeClass('d-none')
                    .html('Event berhasil ditambahkan!')
                    .fadeIn();

                setTimeout(function(){
                    $('#alertSuccess').fadeOut(function(){
                        $(this).addClass('d-none').show().html('');
                    });
                }, 3000);

                let event = res.event;

                table.row.add([
                    event.id,
                    event.team.name,
                    event.school.name,
                    event.start_date,
                    event.end_date,
                    event.description ? event.description.substring(0, 50) + '...': '-',
                    `
                    <button class="btn btn-warning btn-sm editBtn"
                        data-id="${event.id}"
                        data-team="${event.team_id}"
                        data-school="${event.school_id}"
                        data-start="${event.start_date_input}"
                        data-end="${event.end_date_input}"
                        data-description="${event.description}"
                        data-token="${event.attendance_token}"
                        data-absen-start="${event.attendance_start_input}"
                        data-absen-end="${event.attendance_end_input}"
                        data-active="${event.is_attendance_active}"
                        >
                        Edit
                    </button>

                    <form action="/admin/event/${event.id}" method="POST" style="display:inline-block;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin ingin menghapus event ini?')">
                            Delete
                        </button>
                    </form>
                     <a href="/admin/event/${event.id}/participants"
                        class="btn btn-info btn-sm me-1">
                        <i class="bi bi-people"></i> Participant
                    </a>
                    <button 
                        class="btn btn-success btn-sm absenBtn"
                        data-id="${event.id}"
                        data-name="${event.name}"
                        data-team="${event.team.name}"
                        data-school="${event.school.name}"
                        data-start="${event.start_date_input}"
                        data-end="${event.end_date_input}"
                        data-description="${event.description}"
                        data-token="${event.attendance_token}"
                        data-absen-start="${event.attendance_start_input}"
                        data-absen-end="${event.attendance_end_input}"
                        data-active="${event.is_attendance_active}"
                    >
                        <i class="bi bi-info-circle"></i> Info Event
                    </button>
                    `
                ]).draw(false);
                table.columns.adjust().draw(false);

                 $('#addEventForm')[0].reset();
            },
         error: function(xhr){
            if(xhr.status === 422){
                let errors = xhr.responseJSON.errors;
                let errorHtml = '<ul class="mb-0">';

                $.each(errors, function(key, value){
                    errorHtml += '<li>'+value[0]+'</li>';
                });

                errorHtml += '</ul>';

                $('#alertErrorEvent')
                    .removeClass('d-none')
                    .html(errorHtml)
                    .fadeIn();

                setTimeout(function(){
                    $('#alertErrorEvent').fadeOut(function(){
                        $(this).addClass('d-none').show().html('');
                    });
                }, 3000);

            } else {
                $('#alertErrorEvent')
                    .removeClass('d-none')
                    .html('Terjadi kesalahan, silakan coba lagi.')
                    .fadeIn();

                setTimeout(function(){
                    $('#alertErrorEvent').fadeOut(function(){
                        $(this).addClass('d-none').show().html('');
                    });
                }, 3000);
            }
        }
    });
});


//absen
$(document).on('click', '.absenBtn', function () {

    let token  = $(this).data('token');
    let absenstart  = $(this).data('absen-start');
    let absenend    = $(this).data('absen-end');
    let start  = $(this).data('start');
    let end    = $(this).data('end');
    let active = $(this).data('active');

    $('#ViewTeam').text($(this).data('team'));
    $('#ViewSchool').text($(this).data('school'));
     $('#ViewDateTime').html(
        start && end
            ? `<p class="mb-1">Mulai : ${formatTimeNoT(start)}</p>
            <p class="mb-0">Selesai : ${formatTimeNoT(end)}</p>`
            : '-'
    );
    $('#ViewDescription').text($(this).data('description'));
    
    $('#viewToken').text(token ?? '-');
    $('#viewTime').html(
        absenstart && absenend
            ? `<p class="mb-1">Mulai : ${formatTimeNoT(absenstart)}</p>
            <p class="mb-0">Selesai : ${formatTimeNoT(absenend)}</p>`
            : '-'
    );

    if (active == 1) {
        $('#viewStatus')
            .removeClass()
            .addClass('badge bg-success')
            .text('Aktif');
    } else {
        $('#viewStatus')
            .removeClass()
            .addClass('badge bg-secondary')
            .text('Nonaktif');
    }

    $('#absenModal').modal('show');
});


// ADD TEAM
$('#addTeamBtn').click(function () {
    let name = $('#newTeamName').val().trim();
    if (!name) {
        $('#alertErrorTeam')
            .removeClass('d-none')
            .html('Nama tim tidak boleh kosong!')
            .fadeIn();

        setTimeout(function () {
            $('#alertErrorTeam').fadeOut(function () {
                $(this).addClass('d-none').show().html('');
            });
        }, 3000);

        return;
    }

   $.ajax({
        url: "{{ route('team.store') }}",
        method: "POST",
        data: {
            name: name,
            _token: "{{ csrf_token() }}"
        },
        success: function (response) {

            $('#teamTable tbody').append(`
                <tr data-id="${response.team.id}">
                    <td>${response.team.id}</td>
                    <td><input type="text" class="form-control form-control-sm team-name" value="${response.team.name}"></td>
                    <td>
                        <button class="btn btn-warning btn-sm btn-update-team">Update</button>
                        <button class="btn btn-danger btn-sm btn-delete-team">Hapus</button>
                    </td>
                </tr>
            `);
            $('#filterTeam').append(`<option value="${response.team.name}">${response.team.name}</option>`);

            $('select[name="team_id"]').append(`<option value="${response.team.id}">${response.team.name}</option>`);

            $('#newTeamName').val('');

            $('#alertSuccessTeam')
                .removeClass('d-none')
                .html('Tim berhasil ditambahkan!')
                .fadeIn();

            setTimeout(function () {
                $('#alertSuccessTeam').fadeOut(function () {
                    $(this).addClass('d-none').show().html('');
                });
            }, 3000);
        },
         error: function (xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let errorMessages = '';

                $.each(errors, function (key, value) {
                    errorMessages += '<li>' + value[0] + '</li>';
                });

                $('#alertErrorTeam')
                    .removeClass('d-none')
                    .html('<ul class="mb-0">' + errorMessages + '</ul>')
                    .fadeIn();
            } else {
                $('#alertErrorTeam')
                    .removeClass('d-none')
                    .html('Terjadi error, silakan coba lagi.')
                    .fadeIn();
            }

            setTimeout(function () {
                $('#alertErrorTeam').fadeOut(function () {
                    $(this).addClass('d-none').show().html('');
                });
            }, 3000);
        }
    });
});

    //edit team
    $(document).on('click', '.btn-update-team', function () {

        let row = $(this).closest('tr');
        let id  = row.data('id');
        let name = row.find('.team-name').val();

         if (!name) {
            alert('Nama tim tidak boleh kosong');
            return;
        }

        $.ajax({
            url: '/team/update/' + id,
            type: 'PUT',
            data: { 
                _token: "{{ csrf_token() }}",
                name: name
            },
            success: function (res) {
                  let oldName = res.old_name;
                  let newName = res.team.name;

                  row.find('.team-name').val(newName);

                  table.rows().every(function () {
                      let rowData = this.data();
                      let rowNode = this.node();

                      if ($(rowNode).data('team-id') == res.team.id) {
                          rowData[1] = newName;
                          this.data(rowData);
                      }
                  });

                  table.draw(false);

                  $(`select[name="team_id"] option[value="${res.team.id}"]`).text(newName);

                  $(`#filterTeam option[value="${oldName}"]`).val(newName).text(newName);

                 $('#alertSuccessTeam')
                    .removeClass('d-none')
                    .html('Kelas berhasil diperbarui!')
                    .fadeIn();

                setTimeout(function() {
                    $('#alertSuccessTeam').fadeOut(function() {
                        $('#alertSuccessTeam').addClass('d-none').show().html('');
                    });
                }, 3000);
            },
            error: function () {
                alert('Gagal update tim');
            }
        });
    });

    //delete team
   $(document).on('click', '.btn-delete-team', function () {
    if (!confirm('Yakin ingin menghapus tim ini?')) return;

    let row = $(this).closest('tr');
    let id  = row.data('id');
    let teamName = row.find('.team-name').val();

    $.ajax({
        url: `/team/delete/${id}`,
        method: 'DELETE',
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function (response) {
            if (response.success) {
                row.remove();

                if ($('#filterTeam').val() === teamName) {
                    $('#filterTeam').val('').trigger('change');
                }

                $('#filterTeam option').filter(function () {
                    return $(this).text() === teamName;
                }).remove();

                $('select[name="team_id"] option[value="' + id + '"]').remove();

                $('#alertSuccessTeam')
                    .removeClass('d-none')
                    .html(response.message ?? 'Tim berhasil dihapus!')
                    .fadeIn();

                setTimeout(function () {
                    $('#alertSuccessTeam').fadeOut(function () {
                        $(this).addClass('d-none').html('');
                    });
                }, 3000);
            }
        },
        error: function () {
            $('#alertErrorTeam')
                .removeClass('d-none')
                .html('Terjadi kesalahan saat menghapus tim.')
                .fadeIn();

            setTimeout(function () {
                $('#alertErrorTeam').fadeOut(function () {
                    $(this).addClass('d-none').html('');
                });
            }, 3000);
        }
    });
});



    // EDIT EVENT
  $(document).on('click', '.editBtn', function () {

    $('#editEventId').val($(this).data('id'));
    $('#editEventForm select[name="team_id"]').val($(this).data('team'));
    $('#editSchoolId').val($(this).data('school'));
    $('#editStartDate').val($(this).data('start'));
    $('#editEndDate').val($(this).data('end'));
    $('#editDescription').val($(this).data('description'));

    $('#editToken').val($(this).data('token'));
    $('#editAbsenStart').val($(this).data('absen-start'));
    $('#editAbsenEnd').val($(this).data('absen-end'));

    let active = $(this).data('active');
    $('#editActive').prop('checked', active == 1);


    $('#editEventModal').modal('show');
});


    $('#editEventForm').submit(function(e){
    e.preventDefault();
      if (!validateEventTime(
        '#editStartDate',
        '#editEndDate',
        '#editEndDateError'
    )) {
        return;
    }

    if (!validateAttendanceTime(
        '#editStartDate',
        '#editEndDate',
        '#editAbsenStart',
        '#editAbsenEnd',
        '#editAttendanceEndError'
    )) {
        return;
    }


    let id = $('#editEventId').val();

    $.ajax({
        url: '/admin/event/' + id,
        type: 'PUT',
        data: $(this).serialize(),
       success: function(res){
            $('#editEventModal').modal('hide');
             $('#alertErroreditEvent').addClass('d-none').html('');

            $('#alertSuccess')
                .removeClass('d-none')
                .html('Event berhasil update!')
                .fadeIn();

            setTimeout(function(){
                $('#alertSuccess').fadeOut(function(){
                    $(this).addClass('d-none').show().html('');
                });
            }, 3000);

            let event = res.event;
            let absenBtn = $('button.absenBtn[data-id="'+event.id+'"]');

            absenBtn
                .data('absen-start', event.attendance_start_input)
                .data('absen-end', event.attendance_end_input)
                .data('active', event.is_attendance_active);
                
            let btn = $('button[data-id="'+event.id+'"]');

            btn.data('start', event.start_date);
            btn.data('end', event.end_date);

            let editRow = $('button[data-id="' + event.id + '"]').closest('tr');

            table.row(editRow).data([
                event.id,
                event.team.name,
                event.school.name,
                event.start_date,
                event.end_date,
                event.description ? event.description.substring(0, 50) + '...': '-',
                `
                <button class="btn btn-warning btn-sm editBtn"
                    data-id="${event.id}"
                    data-team="${event.team_id}"
                    data-school="${event.school_id}"
                    data-start="${event.start_date_input}"
                    data-end="${event.end_date_input}"
                    data-description="${event.description}"
                    data-token="${event.attendance_token}"
                    data-absen-start="${event.attendance_start_input}"
                    data-absen-end="${event.attendance_end_input}"
                    data-active="${event.is_attendance_active}"
                    >
                    Edit
                </button>

                <form action="/admin/event/${event.id}" method="POST" style="display:inline-block;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Yakin ingin menghapus event ini?')">
                        Delete
                    </button>
                </form>
                 <a href="/admin/event/${event.id}/participants"
                    class="btn btn-info btn-sm me-1">
                    <i class="bi bi-people"></i> Participant
                </a>
                 <button 
                    class="btn btn-success btn-sm absenBtn"
                    data-id="${event.id}"
                    data-name="${event.name}"
                    data-team="${event.team.name}"
                    data-school="${event.school.name}"
                    data-start="${event.start_date_input}"
                    data-end="${event.end_date_input}"
                    data-description="${event.description}"
                    data-token="${event.attendance_token}"
                    data-absen-start="${event.attendance_start_input}"
                    data-absen-end="${event.attendance_end_input}"
                    data-active="${event.is_attendance_active}"
                >
                    <i class="bi bi-info-circle"></i> Info Event
                </button>
                `
            ]).draw(false);

            table.columns.adjust().draw(false);

        },
        error: function(xhr){
            if(xhr.status === 422){
                let errors = xhr.responseJSON.errors;
                let html = '<ul class="mb-0">';
                $.each(errors, function(k,v){
                    html += `<li>${v[0]}</li>`;
                });
                html += '</ul>';

                 $('#alertErroreditEvent')
                    .removeClass('d-none')
                    .html('<ul class="mb-0">'+html+'</ul>');

                setTimeout(function(){
                    $('#alertErroreditEvent').fadeOut(function(){
                        $(this).addClass('d-none').show().html('');
                    });
                }, 3000);

            } else {
                alert('#alertErroreditEvent', 'Gagal update event.');
            }
        }
    });
});



    // DELETE EVENT
   $(document).on('click', '.deleteEventBtn', function(){
    let id = $(this).data('id');
    let row = $(this).closest('tr');

    if(!confirm('Yakin ingin menghapus event ini?')) return;

    $.ajax({
        url: '/admin/event/' + id,
        type: 'DELETE',
        data: { _token: "{{ csrf_token() }}" },
        success: function(){
            $('#eventTable').DataTable().row(row).remove().draw(false);
            showSuccess('Event berhasil dihapus!');
        },
        error: function(){
            alert('Gagal menghapus event');
        }
    });
});


});
</script>

@endsection
