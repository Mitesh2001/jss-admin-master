<div class="modal fade" id="manage-range-officer-modal" tabindex="-1" role="dialog" aria-labelledby="manage-range-officer-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="edit-method-input">@method('PUT')</div>
            <div class="d-none" id="add-new-url">{{ route('admin.range_officers.store') }}</div>

            <form method="post" class="form" id="manage-range-officer-form">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="manage-range-officer-modal-label">Add New Range Officer</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
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

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="discipline-id">Discipline:</label>

                                <select name="discipline_id"
                                    id="discipline-id"
                                    class="form-control"
                                    required
                                >
                                    <option value="">Select discipline</option>

                                    @foreach ($disciplines as $discipline)
                                        <option value="{{ $discipline->id }}">
                                            {{ $discipline->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="added-date">Accreditation Date:</label>

                                <div class="input-group mb-3">
                                    <input type="date"
                                        id="added-date"
                                        class="form-control"
                                        name="added_date"
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
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
