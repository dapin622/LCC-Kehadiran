@extends('layout.app')

@section('content')
    {{-- Page Header --}}
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h4 class="page-title">
                        Siswa
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
                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5v14"/>
                                <path d="M5 12h14"/>
                            </svg>
                            Add Siswa
                        </button>
                    </div>
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
                        <div class="card-header px-4 pt-4 d-flex" style="background-color: white;">
                            <div class="w-100 me-2">
                                <div class="form-label">Search</div>
                                <input type="text" id="globalSearch" class="form-control" placeholder="Search member..."/>
                            </div>
                            <div class="w-100 me-2">
                                <div class="form-label">Sekolah</div>
                                <select id="filterSchool" class="form-select">
                                    <option value="">All</option>
                                    @foreach($schools as $school)
                                        <!-- <option value="{{ $school->id }}">{{ $school->name }} - {{ $school->region }} - {{ $school->province }}</option> -->
                                        <option value="{{ $school->name }}">{{ $school->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                           <div class="w-100">
                            <div class="form-label">Tim</div>
                            <select id="filterTeam" class="form-select">
                                <option value="">All</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team }}">{{ $team }}</option>
                                @endforeach
                            </select>
                        </div>
                         </div>     


                        <div class="table-responsive p-2">
                            <table id="memberTable" class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Anggota</th>
                                    <th>Sekolah</th>
                                    <th>Tim</th>
                                    <th>Kelas</th>
                                    <th>Wilayah</th>
                                    <th>QR Code</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($members as $member)
                                    <tr>
                                        <td>{{ $member->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($member->photo)
                                                    <img src="{{ asset('uploads/foto/'.$member->photo) }}"
                                                         width="60" height="60" class="me-2">
                                                @endif
                                                <span>{{ $member->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $member->school->name ?? '-' }}</td>
                                        <td>{{ $member->team_name }}</td>
                                        <td>{{ $member->class_name }}</td>
                                        <td>{{ $member->school->region ?? '-' }}</td>
                                      
                                        <td>{!! QrCode::size(50)->generate($member->qr_code) !!}</td>
                                        <td>
                                            <a href="#"
                                               class="btn btn-info btn-sm">Detail</a>
                                            <button 
                                            type="button" 
                                            class="btn btn-warning btn-sm btn-edit"
                                            data-id="{{ $member->id }}"
                                            data-name="{{ $member->name }}"
                                            data-nisn="{{ $member->nisn }}"
                                            data-gender="{{ $member->gender }}"
                                            data-school="{{ $member->school_id }}"
                                            data-team="{{ $member->team_name }}"
                                            data-classname="{{ $member->class_name }}"
                                            data-qr="{{ $member->qr_code }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editMemberModal"
                                            >
                                            Edit
                                            </button>

                                            <form action="{{ route('admin.member.destroy', $member->id) }}" method="POST"
                                                  style="display:inline-block;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Yakin ingin menghapus siswa ini?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <form id="addMemberForm" action="{{ route('admin.member.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                        <h5 class="modal-title" id="addMemberModalLabel">Tambah Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div id="alertErrorAdd" class="alert alert-danger d-none"></div>
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
                        <div class="row g-3">
                            <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                            <label class="form-label">NISN</label>
                            <input type="text" name="nisn" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="gender" class="form-select" required>
                                <option value="">Pilih</option>
                                <option value="Male">Laki-Laki</option>
                                <option value="Female">Perempuan</option>
                            </select>
                            </div>

                           <div class="col-md-6">
                                <label class="form-label">Nama Sekolah</label>
                                <select name="school_id" class="form-select" required>
                                    <option value="">Pilih Sekolah</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}">{{ $school->name }} - {{ $school->region }} - {{ $school->province }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tim</label>
                                <input type="text" name="team" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                            <label class="form-label">Kelas</label>
                            <select name="class_name" class="form-select" required>
                                <option value="">Pilih Kelas</option>
                                <option value="X">X</option>
                                <option value="XI">XI</option>
                                <option value="XII">XII</option>
                            </select>
                            </div>

                            <div class="col-md-6">
                            <label class="form-label">QR Code</label>
                            <input type="text" name="qr_code" class="form-control" value="{{ Str::uuid() }}" readonly>
                            </div>

                            <div class="col-md-6">
                            <label class="form-label">Foto</label>
                            <input type="file" name="photo" class="form-control">
                            </div>
                        </div>
                        </div>

                        <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <!-- Edit Member -->
                    <div class="modal fade" id="editMemberModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        <form id="editMemberForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                            <h5 class="modal-title">Edit Siswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div id="alertErrorEdit" class="alert alert-danger d-none"></div>
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
                            <input type="hidden" name="id" id="editId">
                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" id="editName" class="form-control" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                <label class="form-label">NISN</label>
                                <input type="text" name="nisn" id="editNisn" class="form-control" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="gender" id="editGender" class="form-select" required>
                                    <option value="Male">Laki-Laki</option>
                                    <option value="Female">Perempuan</option>
                                </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                <label class="form-label">Sekolah</label>
                                <select name="school_id" id="editSchool" class="form-select" required>
                                    @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }} - {{ $school->region }} - {{ $school->province }}</option>
                                    @endforeach
                                </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                <label class="form-label">Tim</label>
                                <input type="text" name="team" id="editTeam" class="form-control" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                <label class="form-label">Kelas</label>
                                <select name="class_name" id="editClass" class="form-select" required>
                                    <option value="X">X</option>
                                    <option value="XI">XI</option>
                                    <option value="XII">XII</option>
                                </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">QR Code</label>
                                    <input type="text" name="qr_code" id="editQrCode" class="form-control" >
                                </div>

                                <div class="col-md-6 mb-3">
                                <label class="form-label">Foto</label>
                                <input type="file" name="photo" id="editPhoto" class="form-control">
                                </div>

                            </div>
                            </div>
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>

                    </div>
                </div>
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
        var table = $('#memberTable').DataTable({
            searching: true,
            dom: 'lrtip',    
            lengthChange: true, 
            paging: true,      
            info: true ,
        });

        $('#globalSearch').on('keyup', function () {
            table.search(this.value).draw();
        });

        $('#filterSchool').on('change', function () {
            table.column(2).search(this.value).draw();
        });

       $('#filterTeam').on('change', function () {
        var val = this.value;
        if (val) {
            table.column(3).search('^' + val + '$', true, false).draw();
        } else {
            table.column(3).search('').draw();
        }
        });
        

    setTimeout(function(){
            $("#sessionAlert").fadeOut(function(){
                $(this).remove(); 
            });
        }, 3000);
        
        $('#addMemberForm').submit(function(e){
        e.preventDefault(); 
        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                $('#addMemberModal').modal('hide'); 
                $('#alertError').addClass('d-none').html('');

                $('#alertSuccess')
                    .removeClass('d-none')
                    .html('Siswa berhasil ditambahkan!')
                    .fadeIn();

                setTimeout(function(){
                    $('#alertSuccess').fadeOut(function(){
                        $(this).addClass('d-none').show().html('');
                    });
                }, 3000);

                if(!response.success){
                    return; 
                }

                var member = response.member; 
                var photo = member.photo ? '<img src="/uploads/foto/' + member.photo + '" width="60" height="60" class=" me-2">' : '';
                // var qrCode = '<img src="data:image/png;base64,' + member.qr_code_base64 + '" width="40" height="40">';
                var qrCode = member.qr_code_svg;
                // var qrCode = '<img src="data:image/png;base64,' + response.qr_code_png + '" width="50" height="50">';

                table.row.add([
                    member.id,
                    '<div class="d-flex align-items-center">' + photo + '<span>' + member.name + '</span></div>',
                    member.school_name ?? '-',
                    member.team_name,
                    member.class_name,
                    member.region ?? '-',
                    qrCode,
                    `<a href="#" class="btn btn-info btn-sm">Detail</a>
                    <button type="button" class="btn btn-warning btn-sm btn-edit"
                        data-id="${member.id}"
                        data-name="${member.name}"
                        data-nisn="${member.nisn}"
                        data-gender="${member.gender}"
                        data-school="${member.school_id}"
                        data-team="${member.team_name}"
                        data-classname="${member.class_name}"
                        data-qr="${member.qr_code}"
                        data-bs-toggle="modal"
                        data-bs-target="#editMemberModal">
                        Edit
                    </button>
                    <form style="display:inline-block;">@csrf @method("DELETE")<button type="submit" class="btn btn-danger btn-sm">Delete</button></form>`
                ]).draw(false);

                $('#addMemberForm')[0].reset(); 
            },

            
         error: function(xhr){
            if(xhr.status === 422){ 
                let errors = xhr.responseJSON.errors;
                let errorMessages = '';
                $.each(errors, function(key, value){
                    errorMessages += '<li>'+ value[0] + '</li>';
                });

                $('#alertErrorAdd')
                    .removeClass('d-none')
                    .html('<ul class="mb-0">'+errorMessages+'</ul>');

                setTimeout(function(){
                    $('#alertErrorAdd').fadeOut(function(){
                        $(this).addClass('d-none').show().html('');
                    });
                }, 3000);

            } else {
                $('#alertErrorAdd')
                    .removeClass('d-none')
                    .html('Terjadi error, silakan coba lagi.');

                setTimeout(function(){
                    $('#alertErrorAdd').fadeOut(function(){
                        $(this).addClass('d-none').show().html('');
                    });
                }, 3000);
            }
        }

        });
    });

$(document).on('click', '.btn-edit', function () {
    
   $('#editId').val($(this).data('id'));
    $('#editName').val($(this).data('name'));
    $('#editNisn').val($(this).data('nisn'));
    $('#editTeam').val($(this).data('team'));
    $('#editSchool').val($(this).data('school'));
    $('#editClass').val($(this).data('classname'));
    $('#editGender').val($(this).data('gender'));
    $('#editQrCode').val($(this).data('qr'));

    $('#editMemberModal').modal('show');

});

$('#editMemberForm').submit(function(e){
    e.preventDefault();
    let id = $('#editId').val();
    let formData = new FormData(this);

    $.ajax({
        url: '/admin/member/' + id,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response){
            $('#editMemberModal').modal('hide');

            $('#alertSuccess')
              .removeClass('d-none')
              .text('Data siswa berhasil diperbarui!')
              .fadeIn().delay(3000).fadeOut();
              
            let member = response.member;
            let photo = member.photo ? `<img src="/uploads/foto/${member.photo}" width="60" height="60" class="me-2">`: '';
            let qrCode = member.qr_code_svg ?? '<span class="text-danger">No QR</span>';

            let editRow = $('button[data-id="' + member.id + '"]').closest('tr');

             table.row(editRow).data([
                member.id,
                `<div class="d-flex align-items-center">${photo}<span>${member.name}</span></div>`,
                member.school_name,
                member.team_name,
                member.class_name,
                member.region,
                qrCode,
               `<a href="#" class="btn btn-info btn-sm">Detail</a>
                <button type="button" class="btn btn-warning btn-sm btn-edit"
                    data-id="${member.id}"
                    data-name="${member.name}"
                    data-nisn="${member.nisn}"
                    data-gender="${member.gender}"
                    data-school="${member.school_id}"
                    data-team="${member.team_name}"
                    data-classname="${member.class_name}"
                    data-qr="${member.qr_code}"
                    data-bs-toggle="modal"
                    data-bs-target="#editMemberModal">
                    Edit
                </button>
                <form style="display:inline-block;">@csrf @method("DELETE")<button type="submit" class="btn btn-danger btn-sm">Delete</button></form>`
            ]).draw(false);
        },
        error: function(xhr){
            if(xhr.status === 422){ 
                let errors = xhr.responseJSON.errors;
                let errorMessages = '';
                $.each(errors, function(key, value){
                    errorMessages += '<li>'+ value[0] + '</li>';
                });

                $('#alertErrorEdit')
                    .removeClass('d-none')
                    .html('<ul class="mb-0">'+errorMessages+'</ul>');

                setTimeout(function(){
                    $('#alertErrorEdit').fadeOut(function(){
                        $(this).addClass('d-none').show().html('');
                    });
                }, 3000);

            } else {
                alert('Update gagal, coba lagi!');
            }
        }
        });
    });
});

</script>
@endsection
