@extends('layouts.admin.app')
@section('title', 'User')
@section('content')  

<h5 class="card-title">Role <span>| This Year</span></h5>
<button href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-role">
    <i class="bi bi-plus fs-5"></i>
    Add Role
</button>
<table class="table datatable" id="roles-table">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Role</th>
            <th scope="col">Permissions</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Modal -->
@include('pages.roles.modal')
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
                    orderable: false
                },
                {
                    data: 'name',
                },
                {
                    data: 'permissions',
                    orderable: false,
                    render: function(data, type, row) {
                        if (data.length > 0) {
                            let createColor = 'badge-primary';
                            let readColor = 'badge-success';
                            let updateColor = 'badge-warning';
                            let deleteColor = 'badge-danger';
                            let colors = [createColor, readColor, updateColor, deleteColor];
                            let permissions = data.map(permission => permission.name);
                            let badge = permissions.map((permission, index) => {
                                return `<span class="badge ${colors[index % colors.length]} text-white capitalize">${permission}</span>`;

                            }).join(' ');
                            return badge;
                        } else if (row.name == 'super-admin') {
                            return `<span class="badge bg-info-500 text-white capitalize">All Permission</span>`;
                        } else {
                            return `<span class="badge badge-danger text-white capitalize">No Permission</span>`;
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (data.name != 'super-admin') {
                            return `<div class="flex items-center justify-end space-x-2">
                            @can('assing-permission')
                            <button class="btn btn-sm btn-success permission" data-id="${data.id}">Permission</button>
                            @endcan

                            @can('update-role')
                            <button class="btn btn-sm btn-primary edit" data-id="${data.id}">Edit</button>
                            @endcan

                            @can('delete-role')
                            <button class="btn btn-sm btn-danger delete" data-id="${data.id}">Delete</button>
                            @endcan
                            </div>`;
                        }

                        return '';
                    }
                }
            ]
        }

        var table = $('#roles-table');
        var config = {
            dom: "<'row mb-2'<'col-sm-6 d-flex align-items-center justify-conten-start dt-toolbar'l><'col-sm-6 d-flex align-items-center justify-content-end dt-search'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
            processing: true,
            serverSide: true,
            ajax: "{{ route('role.data') }}",
            paging: true,
            ordering: true,
            info: false,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],

            columns: defineColumns()
        };

        initializeDataTable(table, config);

        $('#form-add-role').on('submit', function(e) {
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
                    $('#form-add-role button[type="submit"]').attr('disabled', true);
                    $('#form-add-role button[type="submit"]').html('<iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" icon="line-md:loading-twotone-loop"></iconify-icon><span>Loading</span>');
                },
                success: function(response) {
                    if (response.success) {
                        $('#modal-add-role').modal('hide');
                        $('#form-add-role')[0].reset();
                        toastr.success(response.message);
                        table.DataTable().ajax.reload();
                    } else {
                        toastr.error(response.message);
                    }
                    $('#form-add-role button[type="submit"]').attr('disabled', false);
                    $('#form-add-role button[type="submit"]').html('Submit');
                }

            })
        })

        $(document).on('click', '.edit', function(e) {
            e.preventDefault()
            var data = table.DataTable().row($(this).closest('tr')).data();

            $('#modal-add-role').modal('show');
            $('#modal-add-role').find('#title').text('Edit Role');
            $('#form-add-role').attr('action', '{{ route("role.update") }}');
            $('#form-add-role').append('<input type="hidden" name="_method" value="PUT">');
            $('#form-add-role').append('<input type="hidden" name="id" value="' + data.id + '">');
            $('#form-add-role').find('input[name="role"]').val(data.name);
        })

        $('#modal-add-role').on('hidden.bs.modal', function() {
            $('#modal-add-role').find('#title').text('Add Role');
            $('#form-add-role input[name="_method"]').remove();
            $('#form-add-role input[name="id"]').remove();
            $('#form-add-role').attr('action', '{{ route("role.store") }}');
            $('#form-add-role')[0].reset();
        })

        $(document).on('click', '.delete', function() {
            var id = $(this).data('id')
            console.log(id);
            var result = confirm('Are you sure you want to delete this role?');

            if (result) {
                $.ajax({
                    url: '{{ route("role.destroy") }}',
                    method: "DELETE",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        table.DataTable().ajax.reload();
                    }
                })
            }
        })

        var checkedPermissions = {}

        $(document).on('click', '.permission', function(e) {
            e.preventDefault()
            var data = table.DataTable().row($(this).closest('tr')).data()
            $('#modal-permission-role').modal('show');
            $('#modal-permission-role').find('input[name="role"]').val(data.name);
            checkRolePermissions(data)
        })

        function checkRolePermissions(roleData) {
            var rolePermissions = roleData.permissions;
            var permissionTable = $('#permission-table').DataTable();

            permissionTable.rows().every(function() {
                var rowData = this.data();
                var permissionId = rowData.id;

                var isPermissionOwned = rolePermissions.some(function(permission) {
                    return permission.id === permissionId;
                });

                if (isPermissionOwned || checkedPermissions[permissionId]) {
                    $(this.node()).find('input[type="checkbox"]').prop('checked', true);
                    checkedPermissions[permissionId] = true;
                } else {
                    $(this.node()).find('input[type="checkbox"]').prop('checked', false);
                    delete checkedPermissions[permissionId];
                }
            });
        }

        function defineColumns2() {
            return [{
                    data: 'DT_RowIndex',
                },
                {
                    data: 'name',
                },
                {
                    data: null,
                    class: 'table-td',
                    render: function(data, type, row) {
                        var isChecked = checkedPermissions[data.id] ? 'checked' : '';
                        return `<div class="flex items-center justify-center space-x-2">
                            <input type="checkbox" class="form-check-input" name="permissions[]" value="${data.id}" ${isChecked}>
                        </div>`;
                    }
                }
            ]
        }

        var table2 = $('#permission-table');
        var config2 = {
            dom: "<'row mb-2'<'col-sm-6 d-flex align-items-center justify-conten-start dt-toolbar'l><'col-sm-6 d-flex align-items-center justify-content-end dt-search'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
            parent: 'modal-permission-role',
            ajax: "{{ route('permission.data') }}",
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            columns: defineColumns2(),
        };

        initializeDataTable(table2, config2);

        $('#permission-table').on('change', 'input[type="checkbox"]', function() {
            var permissionId = $(this).val();
            if ($(this).prop('checked')) {
                checkedPermissions[permissionId] = true;
            } else {
                delete checkedPermissions[permissionId];
            }
        });

        $('#form-permission-role').on('submit', function(e) {
            e.preventDefault();
            var permissions = Object.keys(checkedPermissions);
            var form = new FormData(this);
            form.append('permissions', permissions);
            permissions.forEach(function(permission) {
                form.append('permissions[]', permission);
            });
            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: form,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#form-permission-role button[type="submit"]').attr('disabled', true);
                    $('#form-permission-role button[type="submit"]').html('<iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" icon="line-md:loading-twotone-loop"></iconify-icon><span>Loading</span>');
                },
                success: function(response) {
                    if (response.success) {
                        $('#modal-permission-role').modal('hide');
                        $('#form-permission-role')[0].reset();
                        table.DataTable().ajax.reload();
                        toastr.success(response.message);
                    } else {
                        console.log(response);
                        toastr.error(response.message);
                    }
                    $('#form-permission-role button[type="submit"]').attr('disabled', false);
                    $('#form-permission-role button[type="submit"]').html('Submit');
                }
            });
        });

        $('#modal-permission-role').on('hidden.bs.modal', function() {
            $('#form-permission-role input[name="role"]').val('');
            $('#permission-table input[type="checkbox"]').prop('checked', false);
            checkedPermissions = {};
        })
    })
</script>
@endpush