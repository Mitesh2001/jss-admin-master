<div class="modal fade" id="disciplineModel" tabindex="-1" role="dialog" aria-labelledby="disciplineModelTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="disciplineModelTitle">Manage Discipline</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div id="error-container"></div>

            <span id="discipline-add-url" class="d-none">{{ $addRoute }}</span>
            <span id="discipline-edit-url" class="d-none">{{ $editRoute }}</span>
            <span id="discipline-edit-method" class="d-none">@method('PUT')</span>

            <form method="post"
                action="{{ route('admin.individuals.disciplines.store', ['individual' => $individual->id]) }}"
                id="discipline-form"
            >
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <label for="discipline-id">Discipline:</label>

                        <select id="discipline-id" name="discipline_id" class="form-control">
                            @foreach ($disciplineTypes as $disciplineType)
                                <option value="{{ $disciplineType->id }}">
                                    {{ $disciplineType->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <label for="discipline-registered-at">Registered At:</label>

                    <div class="input-group mb-3">
                        <input type="date"
                            id="discipline-registered-at"
                            class="form-control"
                            name="registered_at"
                        >

                        <div class="input-group-prepend">
                            <a class="input-group-text" id="btnGroupAddon" data-toggle>
                                <i class="fa fa-calendar"></i>
                            </a>
                        </div>
                    </div>

                    <label for="discipline-approved-at">Approved At:</label>

                    <div class="input-group mb-3">
                        <input type="date"
                            id="discipline-approved-at"
                            class="form-control"
                            name="approved_at"
                        >

                        <div class="input-group-prepend">
                            <a class="input-group-text" id="btnGroupAddon" data-toggle>
                                <i class="fa fa-calendar"></i>
                            </a>
                        </div>
                    </div>

                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
                        <input type="hidden" name="is_lifetime_member" value="0">

                        <input type="checkbox"
                            class="custom-control-input"
                            id="is-lifetime-member"
                            name="is_lifetime_member"
                            value="1"
                        >

                        <label class="custom-control-label" for="is-lifetime-member">
                            Lifetime Discipline Member
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
