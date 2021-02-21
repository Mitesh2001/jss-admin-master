<div class="col-12 mb-5">
    <div class="card">
        <div class="card-body p-3">
            <h5>Discipline Details</h5>

            <div class="form-group">
                <label for="label">Label:</label>

                <input type="text"
                    name="label"
                    class="form-control"
                    value="{{ old('label', $discipline->label) }}"
                    required
                >
            </div>

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="input-group right-input-addon">
                        <label for="adult-price">Adult Price:</label>

                        <input type="number"
                            id="adult-price"
                            name="adult_price"
                            class="form-control"
                            value="{{ old('adult_price', $discipline->adult_price) }}"
                            required
                        >

                        <div class="input-group-prepend">
                            <a class="input-group-text" id="btnGroupAddon">
                                $
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="input-group right-input-addon">
                        <label for="family-price">Family Price:</label>

                        <input type="number"
                            id="family-price"
                            name="family_price"
                            class="form-control"
                            value="{{ old('family_price', $discipline->family_price) }}"
                            required
                        >

                        <div class="input-group-prepend">
                            <a class="input-group-text" id="btnGroupAddon">
                                $
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="input-group right-input-addon">
                        <label for="pensioner-price">Pensioner Price:</label>

                        <input type="number"
                            id="pensioner-price"
                            name="pensioner_price"
                            class="form-control"
                            value="{{ old('pensioner_price', $discipline->pensioner_price) }}"
                            required
                        >

                        <div class="input-group-prepend">
                            <a class="input-group-text" id="btnGroupAddon">
                                $
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
