<div class="modal fade" id="modal-add-media" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Media</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('media.store') }}" method="POST" id="form-add-media"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Nama File</label>
                        <input type="text" name="file" id="file" class="form-control"
                            placeholder="Masukan file..." required>
                    </div>
                    <div class="form-group">
                        <label>Media</label>
                        <input type="file" name="type[]" id="type[]" multiple class="form-control">
                        <input type="hidden" name="status_izin" id="status_izin" class="form-control" value="pending">
                    </div>
                    <div class="form-group">
                        <label for="file_path">Keterangan</label>
                        <textarea type="text" name="file_path" id="file_path" rows="10" class="form-control"></textarea>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
