<div class="row">
    <div class="col-12 individual-list-container">
        <div class="form-group">
            <label for="individual-id">Select Individual:</label>
            <select class="form-control" name="individual_id" id="individual-id">
                @foreach ($individuals as $individual)
                    <option value="{{ $individual->id }}">
                        {{ $individual->getName() }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label for="dated-at">Receipt Date:</label>
            <div class="input-group mb-3">
                <input type="date"
                    id="dated-at"
                    class="form-control"
                    name="dated_at"
                    value="{{ now() }}"
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
