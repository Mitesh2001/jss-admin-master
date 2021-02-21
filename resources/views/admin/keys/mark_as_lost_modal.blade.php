<div class="modal fade" id="mark-as-lost-modal" tabindex="-1" role="dialog" aria-labelledby="mark-as-lost-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" class="form" id="mark-as-lost-form">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="mark-as-lost-modal-label">Mark As Lost</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <h4 class="text-danger mb-3">Are you 100% sure you want to mark this key as lost?</h4>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="loosed-at">Lost Date:</label>

                                <div class="input-group mb-3">
                                    <input type="date"
                                        id="loosed-at"
                                        class="form-control"
                                        name="loosed_at"
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
                    <button type="submit" class="btn btn-primary">Lost</button>
                </div>
            </form>
        </div>
    </div>
</div>
