<div class="modal fade" id="remove-support-modal" tabindex="-1" role="dialog" aria-labelledby="remove-support-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.firearms.remove_support') }}" method="post" class="form">
                @csrf

                <input type="hidden" name="id" id="firearm-id">

                <div class="modal-header">
                    <h5 class="modal-title" id="remove-support-modal-label">Remove Support</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="support-removed-at">Date:</label>

                                <div class="input-group mb-3">
                                    <input type="date"
                                        id="support-removed-at"
                                        class="form-control"
                                        name="support_removed_at"
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
                                <label for="support-reason">Reason:</label>

                                <textarea name="support_reason"
                                    id="support-reason"
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
