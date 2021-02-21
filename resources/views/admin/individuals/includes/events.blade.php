<div class="col-12 col-md-6 mb-5">
    <div class="card">
        <div class="card-body p-3">
            <div class="row">
                <div class="col-6">
                    <h5>Events</h5>
                </div>
            </div>

            <div id="events-container"></div>

            <button type="button"
                class="btn btn-sm btn-outline-dark float-right add-new-event"
            >
                Add Event
            </button>
        </div>
    </div>
</div>

<template id="event-template">
    <div class="mb-2 event-row">
        <div class="row">
            <div class="col-2 happened-at"></div>

            <div class="col-8">
                <div class="font-weight-bold type"></div>

                <div class="comments"></div>
            </div>

            <div class="col-2">
                <div class="dropdown">
                    <button class="btn btn-secondary px-1 py-0 mb-1 dropdown-toggle remove-down-icon" type="button" data-toggle="dropdown">
                        <i class="fa fa-ellipsis-h"></i>
                    </button>

                    <div class="dropdown-menu">
                        <a class="dropdown-item q-edit pointer"
                            data-event-id=""
                            data-toggle="modal"
                            data-target="#eventModel"
                        >
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <a class="dropdown-item q-trash pointer" data-event-id="">
                            <i class="fa fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
    var iEvents = @json($individualEvents);
    var eventsContainer = $('#events-container');
    var eventTemplate = $('#event-template');
    var eventForm = $('#event-form');
    var putMethodInputContainer = document.getElementById('event-edit-method');
    var putMethodInput = putMethodInputContainer.getElementsByTagName('input')[0];

    $(document).ready(function () {
        $.each(iEvents, function(key, event) {
            eventsContainer.append(eventTemplate.html());
            var eventElement = $(eventsContainer).find('.event-row').last();
            addEvent(eventElement, event);
        });

        $('.add-new-event').on('click', function(e) {
            var eventAddUrl = document.getElementById('event-add-url').innerHTML;
            var eventHappenedAt = document.getElementById('happened-at');

            $('#event-form').attr('action', eventAddUrl);
            $('#event-form')[0].reset();
            $('#eventModel #error-container').html('');

            $('#event-form input[name="_method"]').remove();
            eventHappenedAt._flatpickr.setDate('{{ now()->format("Y-m-d") }}');

            $('#eventModel').modal('show');
        });

        $(document).on("submit", "form#event-form", function(e) {
            e.preventDefault();

            axios.post(
                $(this).attr('action'), $(this).serialize()
            ).then(function (response) {
                showNotice(response.data.type, response.data.message);

                if ($("#event-form").find('input[name="_method"]').length) {
                    editEvent(response.data.data);
                } else {
                    eventsContainer.prepend(eventTemplate.html());
                    var eventElement = $(eventsContainer).find('.event-row').first();
                    addEvent(eventElement, response.data.data);
                }

                $('#eventModel').modal('hide');
            }).catch(function (errorResponse) {
                showValidationErrors(errorResponse.response.data.errors, '#eventModel #error-container');
            });
        });

        $('#events-container').on('click', '.q-edit', function(e) {
            var eventId = $(this).data('eventId');
            var eventRow = $('#event-id-' + eventId);

            eventForm = $('#event-form');
            var eventEditUrl = document.getElementById('event-edit-url').innerHTML;
            eventEditUrl = eventEditUrl.replace('eventIdHere', eventId);
            eventForm.attr('action', eventEditUrl);

            var eventHappenedAt = document.getElementById('happened-at');
            var happenedAtDate = eventRow.find('.happened-at').data('date');
            eventHappenedAt._flatpickr.setDate(happenedAtDate);

            var typeId = eventRow.find('.type').data('typeId');
            $('#event-type-id').val(typeId);

            var comments = eventRow.find('.comments').html();
            $('#event-comments').val(comments);

            eventForm = document.getElementById('event-form');
            eventForm.appendChild(putMethodInput);

            $('#eventModel').modal('show');
        });

        $(eventsContainer).on( "click", "a.q-trash", function(e) {
            var eventId = $(this).data('eventId');
            var eventDeleteUrl = document.getElementById('event-edit-url').innerHTML;
            eventDeleteUrl = eventDeleteUrl.replace('eventIdHere', eventId);

            bootbox.confirm({
                size: "medium",
                message: "Are you sure?",
                callback: function(result) {
                    if (result === true) {
                        axios.delete(
                            eventDeleteUrl,
                            $(this).serialize()
                        ).then(function (response) {
                            showNotice(response.data.type, response.data.message);

                            $("div#event-id-" + eventId).remove();
                        });
                    }
                }
            });
        });
    });

    function addEvent(eventElement, q) {
        eventElement.attr("id", "event-id-" + q.id);

        eventElement.find(".type").data('typeId', q.type.id);
        eventElement.find(".type").html(q.type.label);

        eventElement.find(".comments").html(q.comments);

        eventElement.find(".happened-at").data('date', q.happened_at);
        eventElement.find(".happened-at").html(q.formatted_happened_at);

        eventElement.find(".q-edit").data('eventId', q.id);
        eventElement.find(".q-trash").data('eventId', q.id);
    }

    function editEvent(event) {
        var eventRow = document.getElementById("event-id-" + event.id);

        eventRow.getElementsByClassName("type")[0].dataset.typeId = event.type.id;
        eventRow.getElementsByClassName("type")[0].innerHTML = event.type.label;
        eventRow.getElementsByClassName("comments")[0].innerHTML = event.comments;
        eventRow.getElementsByClassName("happened-at")[0].innerHTML = event.formatted_happened_at;
    }
</script>
@endpush
