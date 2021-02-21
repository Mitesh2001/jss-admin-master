<div class="modal fade" id="manage-firearm-modal" tabindex="-1" role="dialog" aria-labelledby="manage-firearm-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="edit-method-input">@method('PUT')</div>
            <div class="d-none" id="add-new-url">{{ route('admin.firearms.store') }}</div>

            <form action="" method="post" class="form" id="manage-firearm-form">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="manage-firearm-modal-label">Add New Firearm</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="serial">Serial #:</label>

                                <input type="text"
                                    name="serial"
                                    id="serial"
                                    class="form-control"
                                    required
                                >
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="firearm-type">Firearm Type:</label>

                                <select id="firearm-type"
                                    class="form-control selectpicker"
                                    name="firearm_type_id"
                                    required
                                >
                                    <option value="">Select firearm type to add to the list</option>

                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="make">Firearm Make:</label>

                                <input type="text"
                                    name="make"
                                    id="make"
                                    class="form-control"
                                    required
                                >
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="model">Firearm Model:</label>

                                <input type="text"
                                    name="model"
                                    id="model"
                                    class="form-control"
                                    required
                                >
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="calibre">Firearm Calibre:</label>

                                <input type="text"
                                    name="calibre"
                                    id="calibre"
                                    class="form-control"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="discipline-id">Supporting Discipline:</label>

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
                                <label for="support-granted-at">Date Support Granted:</label>
                                <div class="input-group mb-3">

                                    <input type="date"
                                        id="support-granted-at"
                                        class="form-control"
                                        name="support_granted_at"
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

                    <div class="form-group">
                        <label for="individual-ids">Select Individual(s):</label>

                        <select id="individual-ids"
                            class="form-control selectpicker"
                            name="individual_ids[]"
                            required
                            multiple
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

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
