@extends('layouts.admin.app')
@section('title', 'Media')

@section('content')
    <div class="card-header">Halaman Media</div>

    <div class="card-body">
        <a type="button" href="#" class="btn btn-sm fw-bold btn-primary mb-2" data-toggle="modal"
            data-target="#modal-add-media"><i class="nav-icon fas fa-plus-circle"></i>&nbsp;Tambah Media</a>
        <table id="table" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama File</th>
                    <th>User Upload</th>
                    <th>Type</th>
                    <th>Status Izin</th>
                    <th>Aksi</th>
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
                    <th>Aksi</th>
                </tr>
            </tfoot>
        </table>
    </div>
    </div>

    @include('admin.media.modal')
@endsection

@section('scripts')
    <script>
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
                        data: 'file',
                    },
                    {
                        data: 'user.name',
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
                            let detailRoute = "{{ route('media.detail', ':id') }}".replace(':id', row.id);

                            // Status izin download
                            if (row.status_izin == true) {
                                return `<div class="flex items-center justify-end space-x-2 d-block mx-auto">
                                    <a href="${detailRoute}" class="btn btn-sm btn-info"><i class="nav-icon fas fa-plus-circle"></i>&nbsp; Detail</a>
                                    
                                </div>`;
                            } else {
                                return `<div class="flex items-center justify-end space-x-2 d-block mx-auto">
                                
                                <a href="${detailRoute}" class="btn btn-sm btn-info"><i class="nav-icon fas fa-eye"></i>&nbsp;Detail</a>&nbsp;

                                <button class="btn btn-sm btn-warning edit" data-id="${data.id}"><i class="nav-icon fas fa-edit"></i>&nbsp; Edit</button>&nbsp;
                                
                                <button class="btn btn-sm btn-danger delete" data-id="${data.id}"><i class="nav-icon fas fa-trash-alt"></i>&nbsp; Delete</button>&nbsp;
                              
                                </div>`;
                            }

                        }
                    }

                ]
            }
            var table = $('#table');
            var config = {
                dom: "<'row mb-2'<'col-sm-6 d-flex align-items-center justify-conten-start dt-toolbar'l><'col-sm-6 d-flex align-items-center justify-content-end dt-search'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
                processing: true,
                serverSide: true,
                ajax: "{{ route('media.data') }}",
                paging: true,
                ordering: true,
                info: false,
                searching: true,
                lengthChange: true,
                lengthMenu: [10, 25, 50, 100],

                columns: defineColumns()
            };

            initializeDataTable(table, config);

            // Submit form tambah media
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
                        $('#form-add-media button[type="submit"]').attr('disabled', true)
                            .html(
                                'Loading...');
                    },
                    success: function(response) {
                        $('#form-add-media button[type="submit"]').attr('disabled', false)
                            .html(
                                'Simpan');
                        if (response.success) {
                            $('#modal-add-media').modal('hide');
                            $('#form-add-media')[0].reset();
                            toastr.success(response.message);
                            $('#table').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        $('#form-add-media button[type="submit"]').attr('disabled', false)
                            .html(
                                'Simpan');
                        toastr.error('Terjadi kesalahan pada server.');
                    }
                });
            });

            // edit
            $(document).on('click', '.edit', function(e) {
                e.preventDefault();
                var data = table.DataTable().row($(this).closest('tr')).data();

                $('#modal-add-media').modal('show');
                $('#modal-add-media').find('#file').text('Edit Media');
                $('#form-add-media').attr('action', '{{ route('media.update') }}');
                $('#form-add-media').append('<input type="hidden" name="_method" value="PUT">');
                $('#form-add-media').append('<input type="hidden" name="id" value="' + data.id + '">');

                $('#file').val(data.file);
                $('#file_path').val(data.file_path);

                const filePath = '/storage/' + data.file_path;
                const fileType = data.file_path.split('.').pop()
                    .toLowerCase();

                if (fileType === 'jpg' || fileType === 'jpeg' || fileType === 'png' || fileType === 'gif') {
                    $('#image-preview').attr('src', filePath).show();
                    $('#video-preview').hide();
                } else if (fileType === 'mp4' || fileType === 'webm' || fileType === 'ogg') {
                    $('#video-preview').attr('src', filePath).show();
                    $('#image-preview').hide();
                } else {
                    $('#image-preview').hide();
                    $('#video-preview').hide();
                }
            });


            // Detail
            $(document).on('click', '.detail', function(e) {
                e.preventDefault
            })

            // Event delete
            $(document).on('click', '.delete', function() {
                var id = $(this).data('id');
                if (confirm('Apakah anda yakin ingin menghapus data ini?')) {
                    $.ajax({
                        url: '{{ route('media.delete') }}',
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

            // Approve
            $(document).on('click', '.approve', function() {
                var id = $(this).data('id')
                console.log(id);
                var result = confirm('Are you sure you want to update this news?');

                if (result) {
                    $.ajax({
                        url: '{{ route('media.approve', auth()->user()->id) }}',
                        method: "GET",
                        data: {
                            id: id
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.messages);
                                table.DataTable().ajax.reload();
                            } else {
                                toastr.success(response.messages);

                            }

                        }
                    })
                }
            })
        });
    </script>
@endsection
