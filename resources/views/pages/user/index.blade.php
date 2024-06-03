@extends('layouts.admin.app')
@section('title', 'User')
@section('content')  

<h5 class="card-title">User <span>| This Year</span></h5>
<button href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-user">
    <i class="bi bi-plus fs-5"></i>
    Add User
</button>

    <table class="table datatable" id="user-table">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col" class="mr-3">Actions</th>
            </tr>
        </thead>
        <tbody class="fw-semibold text-gray-600">
        </tbody>
    </table>

<!-- Modal -->
@include('pages.user.modal')
@endsection


@push('script')
<!-- jQuery -->
<!-- DataTables CSS and JS -->

<script>
    $(document).ready(function() {
        var table = $('#user-table').DataTable({
            dom: "<'row mb-2'<'col-sm-6 d-flex align-items-center justify-content-start dt-toolbar'l><'col-sm-6 d-flex align-items-center justify-content-end dt-search'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
            processing: true,
            serverSide: true,
            ajax: "{{ route('user.data') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                },
                {
                    data: 'email',
                },
                {
                    data: 'role_name',
                    orderable: false,
                },
                {
                    data: null,
                }
            ],
        });
    });
   
</script>
@endpush

