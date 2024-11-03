@extends('layouts.admin.header')


<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav ml-auto">
                <li>
                    <a class="dropdown-item d-flex align-items-center"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </a>
                </li>

            </ul>
        </nav>
        @include('layouts.admin.sidebar')
        <div class="content-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12 my-3">
                        <div class="card">
                            <div class="card-header">Halaman User</div>

                            <div class="card-body">
                                <a type="button" href="#" class="btn btn-sm fw-bold btn-primary mb-2"
                                    data-toggle="modal" data-target="#modal-add-user">Tambah User</a>
                                <table id="table" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama User</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama User</th>
                                            <th>Email</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function() {
            const table = $("#table")
            table.DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "ajax": "{{ route('user.data') }}",
                "columns": [{
                        data: 'DT_RowIndex',
                        orderable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },

                    {
                        data: null,
                        render: function(data, type, row) {
                            return `<div class="flex items-center justify-end space-x-2">
                                <button class="btn btn-success btn-sm edit" data-id="${data.id}"><i class="bi bi-pencil fs-4 me-2"></i> Edit</button>

                                <button class="btn btn-sm btn-danger delete" data-id="${data.id}"><i class="bi bi-trash fs-4 me-2"></i> Delete</button>
                            </div>`;
                        }
                    }
                ]
            });

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
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                        );
                    },
                    success: function(response) {
                        $('#form-add-user button[type="submit"]').attr('disabled', false)
                            .html('Simpan');

                        if (response.success) {
                            $('#modal-add-user').modal('hide');
                            $('#form-add-user')[0].reset();
                            toastr.success(response.messages);
                            table.DataTable().ajax.reload();
                        } else {
                            toastr.error(response.messages || 'Terjadi kesalahan');
                        }
                        window.location.reload();
                    },
                    error: function(xhr) {
                        $('#form-add-user button[type="submit"]').attr('disabled', false)
                            .html('Simpan');
                        toastr.error('Terjadi kesalahan pada server');
                    }

                });
            });

            $(document).on('click', '.edit', function(e) {
                e.preventDefault();
                var data = table.DataTable().row($(this).closest('tr')).data();

                $('#modal-add-user').modal('show');
                $('#modal-add-user').find('#title').text('Edit user');
                $('#form-add-user').attr('action', '{{ route('user.update', ':id') }}'.replace(
                    ':slug', data.slug));
                $('#form-add-user').append('<input type="hidden" name="_method" value="PUT">');
                $('#form-add-user').append('<input type="hidden" name="slug" value="' + data.slug + '">');
                $('#nama_media').val(data.nama_media);
                $('#slug').val(data.slug);
            });

            $('#modal-add-user').on('hidden.bs.modal', function() {
                $('#modal-add-user').find('#title').text('Add user');
                $('#form-add-user input[name="_method"]').remove();
                $('#form-add-user input[name="slug"]').remove();
                $('#form-add-user').attr('action', '{{ route('user.store') }}');
                $('#form-add-user')[0].reset();
            });


            $(document).on('click', '.delete', function() {
                var id = $(this).data('id')
                console.log(id);
                var result = confirm('Apakah anda yakin ingin menghapus data ini?');

                if (result) {
                    $.ajax({
                        url: '{{ route('user.delete') }}',
                        method: "DELETE",
                        data: {
                            id: id
                        },
                        success: function(response) {
                            table.DataTable().ajax.reload(null, false);
                            toastr.success(response.messages);
                        },
                        error: function(xhr) {
                            toastr.error(response.messages);
                        }
                    })
                }
            })



        });
    </script>

</body>



@include('layouts.admin.footer')
