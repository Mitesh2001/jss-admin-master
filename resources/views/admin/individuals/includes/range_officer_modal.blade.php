<div class="modal fade" id="range-officer-modal" tabindex="-1" role="dialog" aria-labelledby="range-officer-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="range-officer-modal-title">Manage Range Officer Accreditation</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div id="officer-error-container"></div>

            <span id="officer-add-url" class="d-none">{{ $addRoute }}</span>
            <span id="officer-edit-url" class="d-none">{{ $editRoute }}</span>
            <span id="officer-edit-method" class="d-none">@method('PUT')</span>

            <form method="post" id="range-officer-form">
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <label for="officer-discipline-id">Discipline:</label>

                        <select id="officer-discipline-id" name="discipline_id" class="form-control">
                            @foreach ($disciplineTypes as $disciplineType)
                                <option value="{{ $disciplineType->id }}">
                                    {{ $disciplineType->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <label for="officer-added-date">Accredited/Added Date:</label>

                    <div class="input-group mb-3">
                        <input type="date"
                            id="officer-added-date"
                            class="form-control"
                            name="added_date"
                        >

                        <div class="input-group-prepend">
                            <a class="input-group-text" data-toggle>
                                <i class="fa fa-calendar"></i>
                            </a>
                        </div>
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
