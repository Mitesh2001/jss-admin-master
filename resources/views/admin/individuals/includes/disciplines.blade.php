<div class="mb-5">
    <h5>Club Membership Disciplines</h5>

    <div id="disciplines-container"></div>

    <button type="button"
        class="btn btn-sm btn-outline-dark float-right add-new-discipline"
    >
        Add Discipline
    </button>
    <template id="discipline-template">
        <div class="mb-2 discipline-row">
            <div class="row">
                <div class="col-4">
                    <div class="font-weight-bold discipline-text"></div>
                </div>

                <div class="col-3 discipline-registered-at"></div>

                <div class="col-3 discipline-approved-at"></div>

                <div class="col-2">
                    <input type="hidden" class="is-lifetime-member">

                    <div class="dropdown">
                        <button type="button"
                            class="btn btn-secondary px-1 py-0 mb-1 dropdown-toggle remove-down-icon"
                            data-toggle="dropdown"
                        >
                            <i class="fa fa-ellipsis-h"></i>
                        </button>

                        <div class="dropdown-menu">
                            <a class="dropdown-item d-edit pointer"
                                data-discipline-id=""
                                data-toggle="modal"
                                data-target="#disciplineModel"
                            >
                                <i class="fa fa-edit"></i> Edit
                            </a>

                            <a class="dropdown-item d-trash pointer" data-discipline-id="">
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
    var iDiscipline = @json($individualDisciplines);

    var disciplinesContainer = $('#disciplines-container');
    var disciplineTemplate = $('#discipline-template');
    var putMethodInputContainer = document.getElementById('discipline-edit-method');
    var putMethodInput = putMethodInputContainer.getElementsByTagName('input')[0];

    $(document).ready(function () {
        $.each(iDiscipline, function(key, discipline) {
            disciplinesContainer.append(disciplineTemplate.html());
            var disciplineElement = $(disciplinesContainer).find('.discipline-row').last();
            addDiscipline(disciplineElement, discipline);
        });

        $('.add-new-discipline').on('click', function(e) {
            var disciplineAddUrl = document.getElementById('discipline-add-url').innerHTML;
            var disciplineRegisteredAt = document.getElementById('discipline-registered-at');
            var disciplineApprovedAt = document.getElementById('discipline-approved-at');

            $('#discipline-form').attr('action', disciplineAddUrl);
            $('#discipline-form')[0].reset();
            $('#disciplineModel #error-container').html('');

            $('#discipline-id').prop('disabled', false);
            $('.modal #is-lifetime-member').prop('checked', false);

            $('#discipline-form input[name="_method"]').remove();
            $('#discipline-form input[name="discipline_id"]').remove();
            disciplineRegisteredAt._flatpickr.setDate('{{ now()->format("Y-m-d") }}');
            disciplineApprovedAt._flatpickr.setDate('{{ now()->format("Y-m-d") }}');

            $('#disciplineModel').modal('show');
        });

        $(document).on("submit", "form#discipline-form", function(e) {
            e.preventDefault();

            axios.post(
                $(this).attr('action'), $(this).serialize()
            ).then(function (response) {
                showNotice(response.data.type, response.data.message);

                if ($("#discipline-form").find('input[name="_method"]').length) {
                    editDiscipline(response.data.data);
                } else {
                    var disciplines = response.data.data;
                    disciplinesContainer.html('');

                    $.each(disciplines, function(key, discipline) {
                        disciplinesContainer.append(disciplineTemplate.html());
                        var disciplineElement = $(disciplinesContainer).find('.discipline-row').last();
                        addDiscipline(disciplineElement, discipline);
                    });
                }

                $('#disciplineModel').modal('hide');
            }).catch(function (errorResponse) {
                showValidationErrors(
                    errorResponse.response.data.errors,
                    '#disciplineModel #error-container'
                );
            });
        });

        $('#disciplines-container').on('click', '.d-edit', function(e) {
            var disciplineId = $(this).data('disciplineId');

            disciplineForm = $('#discipline-form');
            var disciplineEditUrl = document.getElementById('discipline-edit-url').innerHTML;
            disciplineEditUrl = disciplineEditUrl.replace('disciplineIdHere', disciplineId);

            disciplineForm.attr('action', disciplineEditUrl);

            var disciplineRow = $('#discipline-id-' + disciplineId);

            var disciplineRegisteredAt = document.getElementById('discipline-registered-at');
            var registeredAtDate = disciplineRow.find('.discipline-registered-at').data('date');
            disciplineRegisteredAt._flatpickr.setDate(registeredAtDate);

            var disciplineApprovedAt = document.getElementById('discipline-approved-at');
            var approvedAtDate = disciplineRow.find('.discipline-approved-at').data('date');
            disciplineApprovedAt._flatpickr.setDate(approvedAtDate);

            var typeId = disciplineRow.find('.discipline-text').data('typeId');
            $('#discipline-id').val(typeId).prop('disabled', true);

            var isLifeTimeMember = disciplineRow.find('.is-lifetime-member').val();
            $('.modal #is-lifetime-member').prop('checked', false);
            if (parseInt(isLifeTimeMember)) {
                $('.modal #is-lifetime-member').prop('checked', true);
            }

            disciplineForm.append(putMethodInput);
            disciplineForm.append('<input type="hidden" name="discipline_id" value="' + typeId +'">');

            $('#disciplineModel').modal('show');
        });

        $(disciplinesContainer).on( "click", "a.d-trash", function(e) {
            var disciplineId = $(this).data('disciplineId');
            var disciplineTypeId = $(this).data('disciplineTypeId');
            var disciplineDeleteUrl = document.getElementById('discipline-edit-url').innerHTML;
            disciplineDeleteUrl = disciplineDeleteUrl.replace('disciplineIdHere', disciplineId);

            bootbox.confirm({
                size: "medium",
                message: "Are you sure?",
                callback: function(result) {
                    if (result === true) {
                        axios({
                            method: 'delete',
                            url: disciplineDeleteUrl,
                            data: {
                                discipline_id: disciplineTypeId
                            }
                        }).then(function(response) {
                            showNotice(response.data.type, response.data.message);

                            $("div#discipline-id-" + disciplineId).remove();
                        });
                    }
                }
            });
        });
    });

    function addDiscipline(disciplineElement, q) {
        disciplineElement.attr("id", "discipline-id-" + q.pivot.id);

        var disciplinesText = $("#discipline-id option[value='" + q.pivot.discipline_id + "']").text();
        disciplineElement.find(".discipline-text").data('typeId', q.pivot.discipline_id);
        disciplinesText = q.pivot.is_lifetime_member ? disciplinesText + ' - (Life member)' : disciplinesText;
        disciplineElement.find(".discipline-text").html(disciplinesText);

        disciplineElement.find(".discipline-registered-at").data('date', q.pivot.registered_at);
        disciplineElement.find(".discipline-registered-at").html(q.pivot.registered_at);

        disciplineElement.find(".discipline-approved-at").data('date', q.pivot.approved_at);
        disciplineElement.find(".discipline-approved-at").html(q.pivot.approved_at);

        disciplineElement.find(".is-lifetime-member").val(q.pivot.is_lifetime_member);

        disciplineElement.find(".d-edit").data('disciplineId', q.pivot.id);
        disciplineElement.find(".d-trash").data('disciplineTypeId', q.id);
        disciplineElement.find(".d-trash").data('disciplineId', q.pivot.id);
    }

    function editDiscipline(discipline) {
        var disciplineRow = document.getElementById("discipline-id-" + discipline.i_discipline_id);

        var disciplinesText = $("#discipline-id option[value='" + discipline.discipline_id + "']").text();
        disciplinesText = discipline.is_lifetime_member == 1 ? disciplinesText + ' - (Life member)' : disciplinesText;
        disciplineRow.getElementsByClassName("discipline-text")[0].innerHTML = disciplinesText;

        disciplineRow.getElementsByClassName("discipline-registered-at")[0].innerHTML = discipline.registered_at;
        disciplineRow.getElementsByClassName("discipline-registered-at")[0].dataset.date = discipline.registered_at;

        disciplineRow.getElementsByClassName("discipline-approved-at")[0].innerHTML = discipline.approved_at;
        disciplineRow.getElementsByClassName("discipline-approved-at")[0].dataset.date = discipline.approved_at;

        disciplineRow.getElementsByClassName("is-lifetime-member")[0].value = discipline.is_lifetime_member;
    }
</script>
@endpush
