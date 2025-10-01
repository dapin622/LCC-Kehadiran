@extends('layout.app')

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

                    {{-- Bagian Table --}}
                    <div class="table-responsive p-2">
                        <table id="schoolTable" class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
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
                                        <td>{{ $school->name }}</td>
                                        <td>{{ $school->region }}</td>
                                        <td>{{ $school->province }}</td>
                                        <td>
                                            <form action="{{ route('admin.sekolah.destroy', $school->id) }}" method="POST" style="display:inline-block;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin menghapus sekolah ini?')">Hapus</button>
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
  
      <form id="addSchoolForm" action="{{ route('admin.sekolah.store') }}" method="POST">
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
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
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
                table.row.add([
                    school.id,
                    school.name,
                    school.region,
                    school.province,
                    '<form method="POST" action="/admin/sekolah/'+school.id+'" style="display:inline-block;">' +
                    '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                    '<input type="hidden" name="_method" value="DELETE">' +
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus sekolah ini?\')">Hapus</button>' +
                    '</form>'
                ]).draw(false);

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
});
</script>

@endsection
