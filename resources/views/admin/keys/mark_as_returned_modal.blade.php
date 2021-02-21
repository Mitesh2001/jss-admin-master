<div class="modal fade" id="mark-as-returned-modal" tabindex="-1" role="dialog" aria-labelledby="mark-as-returned-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" class="form" id="mark-as-returned-form">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="mark-as-returned-modal-label">Mark As Returned</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="returned-at">Returned Date:</label>

                                <div class="input-group mb-3">
                                    <input type="date"
                                        id="returned-at"
                                        class="form-control"
                                        name="returned_at"
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
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Return</button>
                </div>
            </form>
        </div>
    </div>
</div>
