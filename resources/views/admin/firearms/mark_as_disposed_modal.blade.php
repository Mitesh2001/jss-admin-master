<div class="modal fade" id="mark-as-disposed-modal" tabindex="-1" role="dialog" aria-labelledby="mark-as-disposed-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.firearms.mark_as_disposed') }}" method="post" class="form">
                @csrf

                <input type="hidden" name="id" id="mark-as-disposed-firearm-id">

                <div class="modal-header">
                    <h5 class="modal-title" id="mark-as-disposed-modal-label">Mark As Disposed</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="mark-as-disposed-at">Date:</label>

                                <div class="input-group mb-3">
                                    <input type="date"
                                        id="mark-as-disposed-at"
                                        class="form-control"
                                        name="mark_as_disposed_at"
                                        value="{{ now()->toDateString() }}"
                                        required
                                    >

                                    <div class="input-group-prepend">
                                        <a class="input-group-text" id="btnGroupAddon">
                                            <i class="fa fa-calendar"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="disposed-reason">Reason:</label>

                                <textarea name="disposed_reason"
                                    id="disposed-reason"
                                    class="form-control"
                                    rows="4"
                                    required
                                ></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
