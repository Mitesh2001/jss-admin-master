<div class="modal fade" id="manage-key-modal" tabindex="-1" role="dialog" aria-labelledby="manage-key-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="edit-method-input">@method('PUT')</div>
            <div class="d-none" id="add-new-url">{{ route('admin.keys.store') }}</div>

            <form method="post" class="form" id="manage-key-form">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="manage-key-modal-label">Add New Key</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="key-number">Key Number:</label>

                                <input type="number"
                                    name="key_number"
                                    id="key-number"
                                    class="form-control"
                                    min="1"
                                    max="1000"
                                    required
                                >
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="key-type">Key Type:</label>

                                <select id="key-type"
                                    class="form-control"
                                    name="key_type"
                                    required
                                >
                                    <option value="1">General</option>
                                    <option value="2">Committee</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="individual-id">Select Individual:</label>

                                <select id="individual-id"
                                    class="form-control selectpicker"
                                    name="individual_id"
                                    required
                                >
                                    <option value="">Select individual to add to the list</option>

                                    @foreach ($individuals as $individual)
                                        <option value="{{ $individual->id }}">
                                            {{ $individual->getName() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="issued-at">Date Issued:</label>
                                <div class="input-group mb-3">

                                    <input type="date"
                                        id="issued-at"
                                        class="form-control"
                                        name="issued_at"
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

                        <div class="col-6">
                            <label for="deposit-amount">Deposit Amount</label>
                            <br>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>

                                <input type="number"
                                    id="deposit-amount"
                                    name="deposit_amount"
                                    class="form-control"
                                    min="0"
                                    max="999999.99"
                                    step="any"
                                    required
                                >
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
