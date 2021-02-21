<div class="mb-5">
    <h5>Range Officer Accreditation</h5>

    <div id="range-officer-container"></div>

    <button type="button"
        class="btn btn-sm btn-outline-dark float-right add-new-range-officer"
    >
        Add Accreditation
    </button>

    <template id="officer-template">
        <div class="mb-2 officer-row">
            <div class="row">
                <div class="col-5">
                    <div class="font-weight-bold officer-discipline-text"></div>
                </div>

                <div class="col-5 officer-added-date"></div>

                <div class="col-2">
                    <div class="dropdown">
                        <button type="button"
                            class="btn btn-secondary px-1 py-0 mb-1 dropdown-toggle remove-down-icon"
                            data-toggle="dropdown"
                        >
                            <i class="fa fa-ellipsis-h"></i>
                        </button>

                        <div class="dropdown-menu">
                            <a class="dropdown-item pointer edit-officer">
                                <i class="fa fa-edit"></i> Edit
                            </a>

                            <a class="dropdown-item pointer remove-officer">
                                <i class="fa fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
    <script>
        var officers = @json($officers);
        var disciplineIds = @json($officers->pluck('discipline_id'));

        for (const key in officers) {
            $('#range-officer-container').append($('#officer-template').html());
            var element = $('#range-officer-container').find('.officer-row').last();
            addOfficer(officers[key], element);
        }

        $('.add-new-range-officer').on('click', function(e) {
            var officerAddUrl = document.getElementById('officer-add-url').innerHTML;

            $('#range-officer-form').attr('action', officerAddUrl);
            $('#range-officer-form')[0].reset();
            $('#range-officer-form input[name=_method]').remove();
            $('#range-officer-modal #officer-error-container').html('');
            var officerAddedAt = document.getElementById('officer-added-date');
            officerAddedAt._flatpickr.setDate('');

            $('#range-officer-modal #officer-discipline-id option').each(function() {
                $(this).prop('disabled', false);

                if (disciplineIds.indexOf(parseInt($(this).val())) != -1) {
                    $(this).prop('disabled', true);
                }
            });

            $('#range-officer-modal').modal('show');
        });

        $(document).on("submit", "form#range-officer-form", function(e) {
            e.preventDefault();

            axios.post(
                $(this).attr('action'), $(this).serialize()
            ).then(function (response) {
                showNotice(response.data.type, response.data.message);
                if (response.data.type == 'error') {
                    return;
                }

                if ($("#range-officer-form").find('input[name="_method"]').length) {
                    editOfficer(response.data.data);
                } else {
                    $('#range-officer-container').append($('#officer-template').html());
                    var element = $('#range-officer-container').find('.officer-row').last();
                    addOfficer(response.data.data, element);
                }

                $('#range-officer-modal').modal('hide');
            }).catch(function (errorResponse) {
                showValidationErrors(
                    errorResponse.response.data.errors,
                    '#range-officer-modal #officer-error-container'
                );
            });
        });

        $('#range-officer-container').on('click', '.edit-officer', function(e) {
            var officerId = $(this).attr('data-id');
            var officerForm = $('#range-officer-form');

            var officerEditUrl = document.getElementById('officer-edit-url').innerHTML;
            officerEditUrl = officerEditUrl.replace('rangeOfficerIdHere', officerId);
            officerForm.attr('action', officerEditUrl);
            officerForm.append($('#officer-edit-method').html());
            $('#range-officer-modal #officer-error-container').html('');

            var addedDate = document.getElementById('officer-added-date');
            addedDate._flatpickr.setDate($(this).attr('data-added-date'));
            var disciplineId = $(this).attr('data-discipline-id');
            document.getElementById('officer-discipline-id').value = disciplineId;

            $('#range-officer-modal #officer-discipline-id option').each(function() {
                if (
                    disciplineIds.indexOf(parseInt($(this).val())) != -1 &&
                    disciplineId != $(this).val()
                ) {
                    $(this).prop('disabled', true);
                }
            });

            $('#range-officer-modal').modal('show');
        });

        $('#range-officer-container').on("click", ".remove-officer", function(e) {
            var id = $(this).attr('data-id');
            var deleteUrl = document.getElementById('officer-edit-url').innerHTML;
            deleteUrl = deleteUrl.replace('rangeOfficerIdHere', id);

            bootbox.confirm({
                size: "medium",
                message: "Are you sure?",
                callback: function(result) {
                    if (result === true) {
                        axios({
                            method: 'delete',
                            url: deleteUrl,
                        }).then(function(response) {
                            showNotice(response.data.type, response.data.message);

                            $("#range-officer-container #officer-id-" + id).remove();
                        });
                    }
                }
            });
        });

        function addOfficer(officer, element) {
            element.attr("id", "officer-id-" + officer.id);
            var disciplinesText = $("#officer-discipline-id option[value='" + officer.discipline_id + "']").text();

            element.find(".officer-discipline-text").html(disciplinesText);
            element.find(".officer-added-date").html(officer.added_date);

            element.find(".edit-officer").attr('data-id', officer.id);
            element.find(".edit-officer").attr('data-discipline-id', officer.discipline_id);
            element.find(".edit-officer").attr('data-added-date', officer.added_date);
            element.find(".remove-officer").attr('data-id', officer.id);
        }

        function editOfficer(officer) {
            var element = $('#range-officer-container #officer-id-'+officer.id);

            var disciplinesText = $("#officer-discipline-id option[value='" + officer.discipline_id + "']").text();
            element.find(".officer-discipline-text").html(disciplinesText);
            element.find(".officer-added-date").html(officer.added_date);

            element.find(".edit-officer").attr('data-id', officer.id);
            element.find(".edit-officer").attr('data-discipline-id', officer.discipline_id);
            element.find(".edit-officer").attr('data-added-date', officer.added_date);
            element.find(".remove-officer").attr('data-id', officer.id);
        }
    </script>
@endpush
