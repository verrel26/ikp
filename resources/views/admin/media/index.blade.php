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
                            <div class="card-header">Halaman Media</div>

                            <div class="card-body">
                                <a type="button" href="#" class="btn btn-sm fw-bold btn-primary mb-2"
                                    data-toggle="modal" data-target="#modal-add-media">Tambah Media</a>
                                <table id="table" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama File</th>
                                            <th>User Upload</th>
                                            <th>Type</th>
                                            <th>Status Izin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama File</th>
                                            <th>User Upload</th>
                                            <th>Type</th>
                                            <th>Status Izin</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin.media.modal')

        </div>

    </div>
    <script script src="https://code.jquery.com/jquery-3.7.1.min.js">
        < script >

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
                "ajax": "{{ route('media.data') }}",
                "columns": [{
                        data: 'DT_RowIndex',
                        orderable: false
                    },
                    {
                        data: 'nama_file'
                    },
                    {
                        data: 'user_upload'
                    },
                    {
                        data: 'type',
                    },
                    {
                        data: 'status_izin',
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let detailRoute = "{{ route('media.detail', ':id') }}".replace(
                                ':id', row.id);

                            if (row.is_approved == false) {
                                return `<div class="justify-content-center">
        <a class="btn btn-md btn-info" href="${detailRoute}">Detail</a> &nbsp;
        <button class="btn btn-md btn-secondary approve" data-id="${data.id}">Approve</button>

    </div>`;
                            } else {
                                return `<div class="justify-content-center">
        <a class="btn btn-md btn-info" href="${detailRoute}">Detail</a> &nbsp;
        <button data-id='${row.id}' class='btn btn-md btn-success edit'>Edit</button> &nbsp;
        <button data-id='${row.id}' class='btn btn-md btn-danger delete'>Hapus</button>
    </div>`;
                            }

                        }
                    }
                ]
            })

            $('#form-add-media').on('submit', function(e) {
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
                        $('#form-add-media button[type="submit"]').attr('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                        );
                    },
                    success: function(response) {
                        $('#form-add-media button[type="submit"]').attr('disabled', false)
                            .html('Simpan');

                        if (response.success) {
                            $('#modal-add-media').modal('hide');
                            $('#form-add-media')[0].reset();
                            toastr.success(response.messages);
                            table.DataTable().ajax.reload();
                        } else {
                            toastr.error(response.messages || 'Terjadi kesalahan');
                        }
                        window.location.reload();
                    },
                    error: function(xhr) {
                        $('#form-add-media button[type="submit"]').attr('disabled', false)
                            .html('Simpan');
                        toastr.error('Terjadi kesalahan pada server');
                    }

                });
            });

            $(document).on('click', '.edit', function(e) {
                e.preventDefault();
                var data = table.DataTable().row($(this).closest('tr')).data();

                $('#modal-add-media').modal('show');
                $('#modal-add-media').find('#title').text('Edit media');
                $('#form-add-media').attr('action', '{{ route('media.update', ':id') }}'.replace(
                    ':slug', data.slug));
                $('#form-add-media').append('<input type="hidden" name="_method" value="PUT">');
                $('#form-add-media').append('<input type="hidden" name="slug" value="' + data.slug + '">');
                $('#nama_media').val(data.nama_media);
                $('#slug').val(data.slug);
            });

            $('#modal-add-media').on('hidden.bs.modal', function() {
                $('#modal-add-media').find('#title').text('Add media');
                $('#form-add-media input[name="_method"]').remove();
                $('#form-add-media input[name="slug"]').remove();
                $('#form-add-media').attr('action', '{{ route('media.store') }}');
                $('#form-add-media')[0].reset();
            });


            $(document).on('click', '.delete', function() {
                var id = $(this).data('id')
                console.log(id);
                var result = confirm('Apakah anda yakin ingin menghapus data ini?');

                if (result) {
                    $.ajax({
                        url: '{{ route('media.delete') }}',
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


            // Approve
            // $(document).on('click', '.approve', function() {
            // var id = $(this).data('id')
            // console.log(id);
            // var result = confirm('Are you sure you want to update this news?');

            // if (result) {
            // $.ajax({
            // url: '{{ route('media.approve') }}',
            // method: "GET",
            // data: {
            // id: id
            // },
            // success: function(response) {
            // if (response.success) {
            // toastr.success(response.messages);
            // table.DataTable().ajax.reload();
            // } else {
            // toastr.success(response.messages);

            // }

            // }
            // })
            // }
            // })
        });
    </script>

</body>



@include('layouts.admin.footer')
