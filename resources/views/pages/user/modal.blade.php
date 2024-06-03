<div class="modal fade" id="modal-add-user" tabindex="-1" aria-labelledby="modal-add-userLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-add-user" action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        {{-- <input type="text" class="form-control" id="name" name="name" required> --}}
                        <select name="role" id="role" class="form-select form-select-solid" required>
                            <option selected="selected" disabled="disabled" value="none">Pilih role</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Masukan Nama...">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required placeholder="Masukan Username...">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Username</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Masukan Email...">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="passwod" class="form-control" id="password" name="password" required placeholder="Masukan Password">
                    </div>
                    <div class="pull-right">
                    <button type="reset" class="btn btn-light">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>