<div class="col-12 mb-3">
    <div class="card">
        <div class="card-body p-3">
            <h5>
                Individual Details
                <a href="{{ route('admin.individuals.edit', ['individual' => $receipt->individual[0]->id]) }}"
                    target="_blank"
                >
                    <i class="fa fa-external-link"></i>
                </a>
            </h5>

            <div class="row">
                <div class="col-6 col-md-4">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text"
                            class="form-control"
                            value="{{ $receipt->individual[0]->first_name . ' ' . $receipt->individual[0]->middle_name . ' ' . $receipt->individual[0]->surname }}"
                            readonly
                        >
                    </div>
                </div>

                <div class="col-6 col-md-4">
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="text"
                            class="form-control"
                            value="{{ $receipt->individual[0]->email_address }}"
                            readonly
                        >
                    </div>
                </div>

                <div class="col-6 col-md-4">
                    <div class="input-group">
                    <label for="dated-at">Receipt Date:</label>

                        <input type="text"
                            id="dated-at"
                            class="form-control"
                            value="{{ $receipt->dated_at }}"
                            name="dated_at"
                            data-input
                        >

                        <div class="input-group-prepend">
                            <a class="input-group-text" id="btnGroupAddon" data-toggle>
                                <i class="fa fa-calendar"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label>Address:</label>
                        <input type="text"
                            class="form-control"
                            value="{{ $receipt->individual[0]->getFullAddress() }}"
                            readonly
                        >
                    </div>
                </div>

                <div class="col-6 col-md-4">
                    <div class="form-group">
                        <label>Suburb:</label>
                        <input type="text"
                            class="form-control"
                            value="{{ $receipt->getEntitySuburbLabel() }}"
                            readonly
                        >
                    </div>
                </div>

                <div class="col-6 col-md-4">
                    <div class="input-group right-input-addon">
                        <label>Total Amount:</label>

                        <input type="text"
                            id="total-amount"
                            class="form-control"
                            value="{{ $receipt->getTotalAmount() }}"
                            readonly
                        >

                        <div class="input-group-prepend">
                            <a class="input-group-text">
                                $
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4">
                    <div class="input-group right-input-addon">
                        <label>Amount Received:</label>

                        <input type="text"
                            id="amount-received"
                            class="form-control"
                            value="{{ $receipt->getReceivedAmount() }}"
                            readonly
                        >

                        <div class="input-group-prepend">
                            <a class="input-group-text">
                                $
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
