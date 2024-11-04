@extends('layouts.admin.app')
@section('title', 'Media')

@section('content')
    <div class="card-header">Halaman Media</div>

    <div class="card-body">
        <a type="button" href="#" class="btn btn-sm fw-bold btn-primary mb-2" data-toggle="modal"
            data-target="#modal-add-media">Tambah Media</a>
        <table id="table" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama File</th>
                    <th>User Upload</th>
                    <th>Type</th>
                    <th>File Path</th>
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
                    <th>File Path</th>
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
                        data: 'user_id',
                    },
                    {
                        data: 'type',
                    },
                    {
                        data: 'file_path',
                    },
                    {
                        data: 'status_izin',
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `<div class="flex items-center justify-end space-x-2">
                        @can('update-media')
                        <button class="btn btn-warning btn-sm edit" data-id="${data.id}"><i class="bi bi-pencil fs-4 me-2"></i> Edit</button>
                        @endcan

                        @can('delete-media')
                        <button class="btn btn-sm btn-danger delete" data-id="${data.id}"><i class="bi bi-trash fs-4 me-2"></i> Delete</button>
                        @endcan
                    </div>`;
                        }
                    }
                ]
            }
            var table = $('#table');
            var config = {
                dom: "<'row mb-2'<'col-sm-6 d-flex align-items-center justify-conten-start dt-toolbar'l><'col-sm-6 d-flex align-items-center justify-content-end dt-search'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
                processing: true,
                serverSide: true,
                ajax: "{{ route('media.index') }}",
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
                        $('#form-add-media button[type="submit"]').attr('disabled', true).html(
                            'Loading...');
                    },
                    success: function(response) {
                        $('#form-add-media button[type="submit"]').attr('disabled', false).html(
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
                        $('#form-add-media button[type="submit"]').attr('disabled', false).html(
                            'Simpan');
                        toastr.error('Terjadi kesalahan pada server.');
                    }
                });
            });

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
        });
    </script>
@endsection
