@extends('layouts.admin.app')
@section('title', 'User')
@section('content')  

<h5 class="card-title">Permission <span>| This Year</span></h5>
<button href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-permission">
    <i class="bi bi-plus fs-5"></i>
    Add Permission
</button>
<table class="table datatable" id="user-table">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Permission</th>
            <th scope="col">Created Date</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Modal -->
@include('pages.permission.modal')
@endsection

@push('script')
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
                    orderable: false,
                },
                {
                    data: 'name',
                },
                {
                    data: 'created_at',
                    render: function(data) {
                        return moment(data).format('DD MMMM YYYY HH:mm:ss');
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<div class="flex items-center justify-end space-x-2">
                        @can('update-permission')
                        <button class="btn btn-warning btn-sm edit" data-id="${data.id}"><i class="bi bi-pencil fs-4 me-2"></i> Edit</button>
                        @endcan

                        @can('delete-permission')
                        <button class="btn btn-sm btn-danger delete" data-id="${data.id}"><i class="bi bi-trash fs-4 me-2"></i> Delete</button>
                        @endcan
                    </div>`;
                    }
                }
            ]
        }

        var table = $('#permissions-table');
        var config = {
            dom: "<'row mb-2'<'col-sm-6 d-flex align-items-center justify-conten-start dt-toolbar'l><'col-sm-6 d-flex align-items-center justify-content-end dt-search'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
            processing: true,
            serverSide: true,
            ajax: "{{ route('permission.data') }}",
            paging: true,
            ordering: true,
            info: false,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],

            columns: defineColumns()
        };

        initializeDataTable(table, config);

        $('#form-add-permission').on('submit', function(e) {
            e.preventDefault();
            var form = new FormData(this)
            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: form,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $('#form-add-permission button[type="submit"]').attr('disabled', true);
                    $('#form-add-permission button[type="submit"]').html('<iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" icon="line-md:loading-twotone-loop"></iconify-icon><span>Loading</span>');
                },
                success: function(response) {
                    if (response.success) {
                        $('#modal-add-permission').modal('hide');
                        $('#form-add-permission')[0].reset();
                        toastr.success(response.message);
                        table.DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
                    $('#form-add-permission button[type="submit"]').attr('disabled', false);
                    $('#form-add-permission button[type="submit"]').html('Submit');
                }

            })
        })

        $(document).on('click', '.edit', function(e) {
            e.preventDefault()
            var data = table.DataTable().row($(this).closest('tr')).data();

            $('#modal-add-permission').modal('show');
            $('#modal-add-permission').find('#title').text('Edit Permission');
            $('#form-add-permission').attr('action', '{{ route("permission.update") }}');
            $('#form-add-permission').append('<input type="hidden" name="_method" value="PUT">');
            $('#form-add-permission').append('<input type="hidden" name="id" value="' + data.id + '">');
            $('#permission').val(data.name);
        })

        $('#modal-add-permission').on('hidden.bs.modal', function() {
            $('#modal-add-permission').find('#title').text('Add Permission');
            $('#form-add-permission input[name="_method"]').remove();
            $('#form-add-permission input[name="id"]').remove();
            $('#form-add-permission').attr('action', '{{ route("permission.store") }}');
            $('#form-add-permission')[0].reset();
        })

        $(document).on('click', '.delete', function() {
            var id = $(this).data('id')
            console.log(id);
            var result = confirm('Are you sure you want to delete this permission?');

            if (result) {
                $.ajax({
                    url: '{{ route("permission.destroy") }}',
                    method: "DELETE",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        table.DataTable().ajax.reload(null, false);
                    }
                })
            }
        })
    })
</script>
@endpush