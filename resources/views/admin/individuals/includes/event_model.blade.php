<div class="modal fade" id="eventModel" tabindex="-1" role="dialog" aria-labelledby="eventModelTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModelTitle">Manage Event</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div id="error-container"></div>

            <span id="event-add-url" class="d-none">{{ $addRoute }}</span>
            <span id="event-edit-url" class="d-none">{{ $editRoute }}</span>
            <span id="event-edit-method" class="d-none">@method('PUT')</span>

            <form method="post"
                action="{{ route('admin.individuals.events.store', ['individual' => $individual->id]) }}"
                id="event-form"
            >
                @csrf

                <div class="modal-body">
                    <label for="happened-at">Date:</label>

                    <div class="input-group mb-3">
                        <input type="date"
                            id="happened-at"
                            class="form-control"
                            name="happened_at"
                        >

                        <div class="input-group-prepend">
                            <a class="input-group-text" id="btnGroupAddon" data-toggle>
                                <i class="fa fa-calendar"></i>
                            </a>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="event-type-id">Type:</label>

                        <select id="event-type-id" name="type_id" class="form-control">
                            @foreach ($eventTypes as $eventType)
                                <option value="{{ $eventType->id }}">
                                    {{ $eventType->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="event-comments">Comments:</label>

                        <input type="text"
                            id="event-comments"
                            class="form-control"
                            name="comments"
                        >
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
