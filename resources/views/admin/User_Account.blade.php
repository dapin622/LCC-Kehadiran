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
                        User Account
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
                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5v14"/>
                                <path d="M5 12h14"/>
                            </svg>
                            Add Account
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
                            <input type="text" id="globalSearch" class="form-control" placeholder="Search..."/>
                        </div>
                          <div class="w-100 me-2">
                                <div class="form-label">Sekolah</div>
                                <select id="filterSchool" class="form-select">
                                    <option value="">All</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->name }}">{{ $school->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                    </div>

                    
                    <div class="table-responsive p-2">
                        <table id="userTable" class="table datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Siswa</th>
                                    <th>Sekolah</th>
                                    <th>Email</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->member->name }}</td>
                                    <td>{{ $user->member->school->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                      <button 
                                          class="btn btn-warning btn-sm btn-edit-user"
                                          data-id="{{ $user->id }}"
                                          data-member="{{ $user->member->id }}"
                                          data-email="{{ $user->email }}"
                                      >
                                          Edit
                                      </button>

                                       <form action="{{ route('admin.user_account.destroy', $user->id) }}" method="POST" class="delete-form"
                                            style="display:inline-block;">
                                          @csrf @method('DELETE')
                                          <button type="submit" class="btn btn-danger btn-sm"
                                                  onclick="return confirm('Yakin ingin menghapus data user ini?')">
                                              Delete
                                          </button>
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

<!-- add user modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="addUserForm" action="{{ route('admin.user_account.store') }}" method="POST">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Tambah Akun Siswa</h5>
        </div>

        <div id="alertError" class="alert alert-danger d-none"></div>

        <div class="modal-body">
          <div class="mb-3">
            <label>Siswa</label>
            <select name="member_id" class="form-select" required>
              <option value="">Pilih Siswa</option>
              @foreach($membersForAdd as $member)
                <option value="{{ $member->id }}">
                  {{ $member->name }} - {{ $member->school->name }}
                </option>
              @endforeach
            </select>
            <small class="text-muted">Pilih siswa yang belum punya akun</small>
          </div>

          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
            <small class="text-muted">Minimal 6 karakter</small>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-primary">Simpan</button>
        </div>

      </form>

    </div>
  </div>
</div>

<!-- edit school modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="editUserForm" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Edit Akun Siswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div id="editAlertError" class="alert alert-danger d-none"></div>

        <div class="modal-body">
          <input type="hidden" id="editUserId">

          <div class="mb-3">
            <label>Siswa</label>
            <select id="editMemberId" name="member_id" class="form-select" required>
              @foreach($membersForEdit as $member)
                <option value="{{ $member->id }}">
                  {{ $member->name }} - {{ $member->school->name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label>Email</label>
            <input type="email" id="editEmail" name="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Password (opsional)</label>
            <input type="password" name="password" class="form-control">
            <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-primary">Simpan Perubahan</button>
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
    var table = $('#userTable').DataTable({
        searching: true,
        dom: 'lrtip',      
        lengthChange: true,
        paging: true,
        info: true
    });

    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

    $('#filterSchool').on('change', function () {
        table.column(2).search(this.value).draw();
    });

    setTimeout(function(){
        $("#sessionAlert").fadeOut(function(){
            $(this).remove(); 
        });
    }, 3000);

    
   $('#addUserForm').submit(function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {

            $('#addUserModal').modal('hide');
            $('#alertError').addClass('d-none').html('');

            $('#alertSuccess')
                .removeClass('d-none')
                .html('Akun siswa berhasil ditambahkan!')
                .fadeIn();

            setTimeout(function () {
                $('#alertSuccess').fadeOut(function () {
                    $(this).addClass('d-none').show().html('');
                });
            }, 3000);

            let user = response.user;

            let deleteUrl = "{{ route('admin.user_account.destroy', ':id') }}"
                .replace(':id', user.id);

            table.row.add([
                user.id,
                user.member.name,
                user.member.school.name,
                user.email,
                `
                <button 
                    class="btn btn-warning btn-sm btn-edit-user"
                    data-id="${user.id}"
                    data-member="${user.member.id}"
                    data-email="${user.email}">
                    Edit
                </button>

                <form method="POST" action="${deleteUrl}" style="display:inline-block;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-sm">
                        Delete
                    </button>
                </form>
                `
            ]).draw(false);

            table.columns.adjust().draw();
            initButtonEvents?.();

            $('#addUserForm')[0].reset();
        },

        error: function (xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let errorMessages = '';

                $.each(errors, function (key, value) {
                    errorMessages += '<li>' + value[0] + '</li>';
                });

                $('#alertError')
                    .removeClass('d-none')
                    .html('<ul class="mb-0">' + errorMessages + '</ul>');

                setTimeout(function () {
                    $('#alertError').fadeOut(function () {
                        $(this).addClass('d-none').show().html('');
                    });
                }, 3000);
            } else {
                $('#alertError')
                    .removeClass('d-none')
                    .html('Terjadi error, silakan coba lagi.');
            }
        }
    });
});



    
// edit
$(document).on('click', '.btn-edit-user', function () {
    $('#editUserId').val($(this).data('id'));
    $('#editMemberId').val($(this).data('member'));
    $('#editEmail').val($(this).data('email'));

    $('#editUserModal').modal('show');
});


$('#editUserForm').submit(function (e) {
    e.preventDefault();

    let id = $('#editUserId').val();
    let formData = new FormData(this);
    formData.append('_method', 'PUT');

    $.ajax({
        url: '/admin/user_account/' + id,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {

            $('#editUserModal').modal('hide');

            $('#alertSuccess')
                .removeClass('d-none')
                .text('Akun siswa berhasil diperbarui!')
                .fadeIn();

            setTimeout(() => {
                $('#alertSuccess').fadeOut(() => {
                    $('#alertSuccess').addClass('d-none').html('');
                });
            }, 3000);

            let user = response.user;

            let deleteUrl = "{{ route('admin.user_account.destroy', ':id') }}"
                .replace(':id', user.id);

            let row = $('button[data-id="' + user.id + '"]').closest('tr');

            table.row(row).data([
                user.id,
                user.member.name,
                user.member.school.name,
                user.email,
                `
                <button 
                    class="btn btn-warning btn-sm btn-edit-user"
                    data-id="${user.id}"
                    data-member="${user.member.id}"
                    data-email="${user.email}">
                    Edit
                </button>

                <form method="POST" action="${deleteUrl}" style="display:inline-block;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-sm">
                        Delete
                    </button>
                </form>
                `
            ]).invalidate().draw(false);

            table.columns.adjust().draw(false);
            initButtonEvents?.();
        },

        error: function (xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let errorMessages = '';

                $.each(errors, function (key, value) {
                    errorMessages += '<li>' + value[0] + '</li>';
                });

                $('#editAlertError')
                    .removeClass('d-none')
                    .html('<ul class="mb-0">' + errorMessages + '</ul>');

                setTimeout(function () {
                    $('#editAlertError').fadeOut(function () {
                        $(this).addClass('d-none').show().html('');
                    });
                }, 3000);
            } else {
                alert('Gagal memperbarui akun user!');
            }
        }
    });
});



});
</script>

@endsection
