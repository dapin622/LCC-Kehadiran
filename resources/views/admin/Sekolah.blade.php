@extends('layout.app')

<style>
    
.alert-danger ul {
    list-style-type: none; 
    padding-left: 0;       
    margin-bottom: 0;
}
.alert-danger li {
    margin-left: 0;
}

table.dataTable td {
  white-space: normal !important;
  word-wrap: break-word;
  max-width: 200px; 
  vertical-align: middle;
}


</style>

@section('content')
<div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h4 class="page-title">
                        Sekolah
                    </h4>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-success d-none d-sm-inline-block">
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
                        </a>
                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSchoolModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5v14"/>
                                <path d="M5 12h14"/>
                            </svg>
                            Add Sekolah
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

                    {{-- Bagian Search & Filter --}}
                    <div class="card-header px-4 pt-4 d-flex" style="background-color: white;">
                        <div class="w-100 me-2">
                            <div class="form-label">Search</div>
                            <input type="text" id="globalSearch" class="form-control" placeholder="Search sekolah..."/>
                        </div>
                        <div class="w-100 me-2">
                            <div class="form-label">Wilayah</div>
                            <select id="filterRegion" class="form-select">
                                <option value="">All</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region }}">{{ $region }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-100">
                            <div class="form-label">Provinsi</div>
                            <select id="filterProvince" class="form-select">
                                <option value="">All</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province }}">{{ $province }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    
                    <div class="table-responsive p-2">
                        <table id="schoolTable" class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Logo Sekolah</th>
                                    <th>Nama Sekolah</th>
                                    <th>Wilayah</th>
                                    <th>Provinsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schools as $school)
                                    <tr>
                                        <td>{{ $school->id }}</td>
                                        <td>
                                            @if($school->photo)
                                            <img src="{{ asset('uploads/foto/'.$school->photo) }}" alt="Foto" width="60" height="60" style="object-fit: cover; border-radius: 6px;">
                                            @else
                                            <span class="text-muted">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td>{{ $school->name }}</td>
                                        <td>{{ $school->region }}</td>
                                        <td>{{ $school->province }}</td>
                                        <td>
                                             <button type="button" 
                                                    class="btn btn-warning btn-sm btn-edit-school"
                                                    data-id="{{ $school->id }}"
                                                    data-name="{{ $school->name }}"
                                                    data-region="{{ $school->region }}"
                                                    data-province="{{ $school->province }}"
                                                    data-photo="{{ $school->photo ? asset('uploads/foto/'.$school->photo) : '' }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.sekolah.destroy', $school->id) }}" method="POST" style="display:inline-block;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin menghapus sekolah ini?')">Delete</button>
                                            </form>
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

<div class="modal fade" id="addSchoolModal" tabindex="-1" aria-labelledby="addSchoolModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
  
      <form id="addSchoolForm" action="{{ route('admin.sekolah.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Sekolah</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div id="alertError" class="alert alert-danger d-none"></div>
        <div class="modal-body">
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
            <label class="form-label">Nama Sekolah</label>
            <input type="text" name="name" class="form-control" style="text-transform: uppercase;" required>
            </div>
          <div class="mb-3">
            <label class="form-label">Wilayah</label>
            <input type="text" name="region" class="form-control" style="text-transform: uppercase;" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Provinsi</label>
            <input type="text" name="province" class="form-control" style="text-transform: uppercase;" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Foto Sekolah</label>
            <input type="file" name="photo" class="form-control">
        </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- edit school modal -->
<div class="modal fade" id="editSchoolModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="editSchoolForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Sekolah</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div id="editAlertError" class="alert alert-danger d-none"></div>

        <div class="modal-body">
          <input type="hidden" id="editSchoolId" name="id">

          <div class="mb-3">
            <label class="form-label">Nama Sekolah</label>
            <input type="text" name="name" id="editName" class="form-control" style="text-transform: uppercase;" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Wilayah</label>
            <input type="text" name="region" id="editRegion" class="form-control" style="text-transform: uppercase;" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Provinsi</label>
            <input type="text" name="province" id="editProvince" class="form-control" style="text-transform: uppercase;" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Foto Sekolah</label><br>
            <img id="editPhotoPreview" src="" width="60" height="60" class="mb-2 rounded" style="object-fit:cover; display:none;">
            <input type="file" name="photo" id="editPhoto" class="form-control">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    var table = $('#schoolTable').DataTable({
        searching: true,
        dom: 'lrtip',      
        lengthChange: true,
        paging: true,
        info: true
    });

    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

   $('#filterRegion, #filterProvince').on('change', function () {
    let region = $('#filterRegion').val();
    let province = $('#filterProvince').val();

    $.ajax({
        url: "{{ route('admin.sekolah.filter') }}",
        type: "GET",
        data: { region: region, province: province },
        success: function(data) {
            table.clear(); 
            $.each(data, function(i, school) {
                table.row.add([
                    school.id,
                    school.name,
                    school.region,
                    school.province,
                    '<form method="POST" action="/admin/sekolah/'+school.id+'" style="display:inline-block;">@csrf @method("DELETE")<button type="submit" class="btn btn-danger btn-sm">Hapus</button></form>'
                ]);
            });
            table.draw();
            }
        });
    });

    setTimeout(function(){
        $("#sessionAlert").fadeOut(function(){
            $(this).remove(); 
        });
    }, 3000);

    $('#addSchoolForm').submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                $('#addSchoolModal').modal('hide');
                $('#alertError').addClass('d-none').html('');

                $('#alertSuccess')
                        .removeClass('d-none')
                        .html('Sekolah berhasil ditambahkan!')
                        .fadeIn();
                        
                 setTimeout(function(){
                    $('#alertSuccess').fadeOut(function(){
                        $(this).addClass('d-none').show().html('');
                    });
                }, 3000);
                
                var school = response.school;
                var photoHtml = response.photo_url ? '<img src="'+response.photo_url+'" width="60" height="60" style="object-fit:cover;border-radius:6px;">'
                : '<span class="text-muted">Tidak ada</span>';
                
                var deleteUrl = "{{ route('admin.sekolah.destroy', ':id') }}".replace(':id', school.id);

                table.row.add([
                    school.id,
                    photoHtml,
                    school.name,
                    school.region,
                    school.province,
                   `
                <button type="button" 
                    class="btn btn-warning btn-sm btn-edit-school"
                    data-id="${school.id}"
                    data-name="${school.name}"
                    data-region="${school.region}"
                    data-province="${school.province}"
                    data-photo="/uploads/foto/${school.photo ?? ''}">
                    Edit
                </button>

                <form method="POST" action="${deleteUrl}" class="delete-form" style="display:inline-block;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
                `
                ]).draw(false);
                
                table.columns.adjust().draw();
                initButtonEvents();

                 if($("#filterRegion option[value='"+school.region+"']").length === 0){
                    $('#filterRegion').append('<option value="'+school.region+'">'+school.region+'</option>');
                }

                if($("#filterProvince option[value='"+school.province+"']").length === 0){
                    $('#filterProvince').append('<option value="'+school.province+'">'+school.province+'</option>');
                }

                $('#addSchoolForm')[0].reset();
            },
         error: function(xhr){
            if(xhr.status === 422){ 
                let errors = xhr.responseJSON.errors;
                let errorMessages = '';
                $.each(errors, function(key, value){
                    errorMessages += '<li>'+ value[0] + '</li>';
                });

                $('#alertError').removeClass('d-none').html('<ul class="mb-0">'+errorMessages+'</ul>');

                setTimeout(function(){
                    $('#alertError').fadeOut(function(){
                        $(this).addClass('d-none').show().html('');
                    });
                }, 3000); 
            } else {
                $('#alertError').removeClass('d-none').html('Terjadi error, silakan coba lagi.');

                setTimeout(function(){
                    $('#alertError').fadeOut(function(){
                        $(this).addClass('d-none').show().html('');
                    });
                }, 3000);
            }
        }

        });
    });
    
    function initButtonEvents() {
    $(document).off('submit', '.delete-form').on('submit', '.delete-form', function(e){
        e.preventDefault();

        const form = $(this);
        const row = form.closest('tr');

        if (!confirm('Yakin ingin menghapus sekolah ini?')) return;

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response){
                $('#schoolTable').DataTable().row(row).remove().draw(false);

                $('#alertSuccess')
                    .removeClass('d-none')
                    .text(response.message || 'Sekolah berhasil dihapus!')
                    .fadeIn();

                setTimeout(function(){
                    $('#alertSuccess').fadeOut(function(){
                        $(this).addClass('d-none').show().html('');
                    });
                }, 3000);
            },
            error: function(xhr){
                alert('Gagal menghapus sekolah, silakan coba lagi.');
            }
        });
    });
}


    // Edit
$(document).on('click', '.btn-edit-school', function () {
    $('#editSchoolId').val($(this).data('id'));
    $('#editName').val($(this).data('name'));
    $('#editRegion').val($(this).data('region'));
    $('#editProvince').val($(this).data('province'));
    $('#editPhotoPreview').attr('src', $(this).data('photo'));

    $('#editSchoolModal').modal('show');
});

$('#editSchoolForm').submit(function (e) {
    e.preventDefault();
    let id = $('#editSchoolId').val();
    let formData = new FormData(this);

    $.ajax({
        url: '/admin/sekolah/' + id,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            $('#editSchoolModal').modal('hide');

            $('#alertSuccess')
                .removeClass('d-none')
                .text('Data sekolah berhasil diperbarui!')
                .fadeIn().delay(3000).fadeOut();

            let school = response.school;
            let timestamp = new Date().getTime();
            let photo = school.photo
                ? `<img src="/uploads/foto/${school.photo}?v=${timestamp}" width="60" height="60" class="me-2 rounded">`
                : '';

            let deleteUrl = "{{ route('admin.sekolah.destroy', ':id') }}".replace(':id', school.id);

            let editRow = $('button[data-id="' + school.id + '"]').closest('tr');

            table.row(editRow).data([
                school.id,
                photo,
                school.name,
                school.region,
                school.province,
                `
                <button type="button" 
                    class="btn btn-warning btn-sm btn-edit-school"
                    data-id="${school.id}"
                    data-name="${school.name}"
                    data-region="${school.region}"
                    data-province="${school.province}"
                    data-photo="/uploads/foto/${school.photo ?? ''}">
                    Edit
                </button>

                <form method="POST" action="${deleteUrl}" class="delete-form" style="display:inline-block;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
                `
            ]).invalidate().draw(false);

            table.columns.adjust().draw(false);
            initButtonEvents();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let errorMessages = '';
                $.each(errors, function (key, value) {
                    errorMessages += '<li>' + value[0] + '</li>';
                });

                $('#alertErrorEdit')
                    .removeClass('d-none')
                    .html('<ul class="mb-0">' + errorMessages + '</ul>');

                setTimeout(function () {
                    $('#alertErrorEdit').fadeOut(function () {
                        $(this).addClass('d-none').show().html('');
                    });
                }, 3000);
            } else {
                alert('Gagal memperbarui sekolah!');
            }
        }
    });
});

});
</script>

@endsection
