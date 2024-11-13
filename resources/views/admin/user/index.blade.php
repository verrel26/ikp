@extends('layouts.admin.app')
@section('title', 'User')

@section('content')
    <div class="card-header">Halaman User</div>

    <div class="card-body">
        <a type="button" href="#" class="btn btn-sm fw-bold btn-primary mb-2" data-toggle="modal"
            data-target="#modal-add-user">Tambah User</a>

        <table id="table" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </tfoot>
        </table>
    </div>

    @include('admin.user.modal')
@endsection

@section('scripts')
    <script>
        // Determine if the logged-in user is an admin
        const isAdmin = @json(Auth::user()->name == 'Admin');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            function defineColumns() {
                return [{
                        data: 'DT_RowIndex',
                        orderable: false
                    },
                    {
                        data: 'name',
                    },
                    {
                        data: 'email',
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            // Conditionally render action buttons for admin only
                            if (isAdmin) {
                                return `
                                    <div class="flex items-center justify-end space-x-2">
                                        <button class="btn btn-warning btn-sm edit" data-id="${data.id}"><i class="nav-icon fas fa-pencil-alt"></i>&nbsp; Edit</button>&nbsp;
                                        <button class="btn btn-sm btn-danger delete" data-id="${data.id}"><i class="nav-icon fas fa-trash-alt"></i>&nbsp; Delete</button>
                                    </div>`;
                            }
                            return ''; // No buttons for non-admins
                        }
                    }
                ];
            }

            var table = $('#table');
            var config = {
                dom: "<'row mb-2'<'col-sm-6 d-flex align-items-center justify-conten-start dt-toolbar'l><'col-sm-6 d-flex align-items-center justify-content-end dt-search'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.data') }}",
                paging: true,
                ordering: true,
                info: false,
                searching: true,
                lengthChange: true,
                lengthMenu: [10, 25, 50, 100],

                columns: defineColumns()
            };

            initializeDataTable(table, config);

            // Submit form tambah user
            $('#form-add-user').on('submit', function(e) {
                e.preventDefault();
                var form = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    method: "POST",
                    data: form,
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $('#form-add-user button[type="submit"]').attr('disabled', true).html(
                            'Loading...');
                    },
                    success: function(response) {
                        $('#form-add-user button[type="submit"]').attr('disabled', false).html(
                            'Simpan');
                        if (response.success) {
                            $('#modal-add-user').modal('hide');
                            $('#form-add-user')[0].reset();
                            toastr.success(response.message);
                            $('#table').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        $('#form-add-user button[type="submit"]').attr('disabled', false).html(
                            'Simpan');
                        toastr.error('Terjadi kesalahan pada server.');
                    }
                });
            });

            // Edit
            $(document).on('click', '.edit', function(e) {
                e.preventDefault();
                var data = table.DataTable().row($(this).closest('tr')).data();

                $('#modal-add-user').modal('show');
                $('#modal-add-user').find('#name').text('Edit User');
                $('#form-add-user').attr('action', '{{ route('user.update') }}');
                $('#form-add-user').append('<input type="hidden" name="_method" value="PUT">');
                $('#form-add-user').append('<input type="hidden" name="id" value="' + data.id + '">');

                $('#name').val(data.name);
                $('#email').val(data.email);
            });

            // Event delete
            $(document).on('click', '.delete', function() {
                var id = $(this).data('id');
                if (confirm('Apakah anda yakin ingin menghapus data ini?')) {
                    $.ajax({
                        url: '{{ route('user.delete') }}',
                        method: "DELETE",
                        data: {
                            id: id
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            $('#table').DataTable().ajax.reload();
                        },
                        error: function() {
                            toastr.error('Terjadi kesalahan saat menghapus data.');
                        }
                    });
                }
            });
        });
    </script>
@endsection
