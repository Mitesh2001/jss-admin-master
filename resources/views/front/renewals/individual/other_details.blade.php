<div class="tab-pane" id="other-details">
    <div id="family-container">
        @include('front.renewals.individual.family_members')

        <button type="button"
            class="btn btn-fill btn-secondary pull-right"
            id="reset-family-members"
        >
            Reset Family Members and Disciplines
        </button>

        <div class="clearfix"></div>
    </div>

    <div id="disciplines-container">
        <div class="alert alert-warning" role="alert">
            Please review your disciplines, and remove any if necessary. If you wish to join a discipline, please speak to the respective discipline captain prior to completing your renewal.
        </div>

        <h3>Disciplines</h3>

        <table class="table table-borderless table-striped-even table-fonts" id="discipline-table">
            @if ($isIndividualWithoutDisciplines)
                <caption class="text-center alert alert-danger">
                    You do not seem to be associated with at least one discipline. Please email info@jarrahdaleshooters.org.au for assistance. Alternatively you can also phone Callen on 0422 522 540 or Daniel on 0402 503 075.
                </caption>
            @endif

            <thead class="table-danger">
                <tr>
                    <th>
                        <i class="fa fa-user"></i>
                        Discipline
                    </th>

                    <th>
                        <i class="fa fa-usd"></i>
                        Price
                    </th>

                    <th></th>
                </tr>
            </thead>

            <tbody></tbody>
        </table>

        <table class="table table-borderless table-striped-even table-fonts"
            id="family-discipline-table"
        >
            @if($isFamilyWithoutDisciplines)
                <caption class="text-center alert alert-danger">
                    One or more of your family members are not associated with a discipline. Please contact your range officer/captain to have this rectified. Alternatively you can remove that family member from your family membership and proceed.
                </caption>
            @endif

            <thead class="table-danger">
                <tr>
                    <th>
                        <i class="fa fa-user"></i>
                        Discipline
                    </th>

                    <th>
                        <i class="fa fa-usd"></i>
                        Price
                    </th>

                    <th></th>
                </tr>
            </thead>

            <tbody></tbody>
        </table>

        <button type="button"
            class="btn btn-fill btn-secondary pull-right"
            id="reset-family-disciplines"
        >
            Reset Disciplines
        </button>

        <button type="button"
            class="btn btn-fill btn-secondary pull-right"
            id="reset-disciplines"
        >
            Reset Disciplines
        </button>

        <div class="clearfix"></div>
    </div>


    <table id="discipline-template" class="hidden">
        <tbody>
            <tr class="discipline-row" data-id="">
                <td class="discipline-label"></td>

                <td>
                    $<span class="discipline-price"></span>
                </td>

                <td>
                    <input type="hidden" name="disciplines[]" class="discipline-id" value="">

                    <i class="fa fa-times-circle remove-discipline pointer"></i>
                </td>
            </tr>
        </tbody>
    </table>

    <table id="family-discipline-template" class="hidden">
        <tbody>
            <tr class="discipline-row" data-id="">
                <td class="discipline-label"></td>

                <td>
                    $<span class="discipline-price"></span>
                </td>

                <td>
                    <input type="hidden" name="family_disciplines[]" class="discipline-id" value="">
                    <input type="hidden" name="individuals[]" class="individual-id" value="">

                    <span class="action-container"></span>
                    <i class="fa fa-times-circle remove-family-discipline pointer"></i>
                </td>
            </tr>
        </tbody>
    </table>

    @include('front.renewals.individual.includes.edit_family_member_discipline')
</div>

@push('scripts')
    <script>
        var disciplines = @json(optional($individual)->disciplines) || [];
        var fIndividualDisciplineIds = @json($familyIndividualDisciplineIds) || [];
        var originalFIndividualDisciplineIds = Object.assign({}, fIndividualDisciplineIds);
        var individuals = @json(optional($family)->individuals) || [];

        $(document).ready(function() {
            setDisciplines();

            if (familyIndividualIds.length) {
                setFamilyDisciplines(fIndividualDisciplineIds);
            }

            $('#reset-disciplines').click(function() {
                bootbox.confirm({
                    size: "medium",
                    message: "Are you sure you want to reset disciplines?",
                    callback: function(result) {
                        if (result === true) {
                            $('#discipline-table tbody').html('');
                            setDisciplines();

                            updateRenewalPrice();
                        }
                    }
                });
            });

            $('#reset-family-disciplines').click(function() {
                bootbox.confirm({
                    size: "medium",
                    message: "Are you sure you want to reset disciplines?",
                    callback: function(result) {
                        if (result === true) {
                            $('#family-discipline-table tbody').html('');
                            setFamilyDisciplines(fIndividualDisciplineIds);

                            updateRenewalPrice();
                        }
                    }
                });
            });

            $('#disciplines-container').on('click', '.remove-discipline', function() {
                if ($('#discipline-table tbody tr').length == 1) {
                    bootbox.alert("You must have at least one discipline.");

                    return;
                }

                var disciplineRow = $(this).parent().parent();

                bootbox.confirm({
                    size: "medium",
                    message: "Are you sure you want to remove this discipline?",
                    callback: function(result) {
                        if (result === true) {
                            $('#reset-disciplines').show();
                            disciplineRow.remove();
                            updateRenewalPrice();
                        }
                    }
                });
            });

            $('#disciplines-container').on('click', '.remove-family-discipline', function() {
                var disciplineRow = $(this).parent().parent();
                var familyIndividualId = $(disciplineRow).attr('data-individual-id');

                if (
                    $('#family-discipline-table [data-individual-id=' + familyIndividualId + ']').length == 1 &&
                    $('#family-discipline-table tbody tr[data-individual-id=0]').length == 0
                ) {
                    bootbox.alert("Members must have at least one discipline assigned.");

                    return;
                }

                var isAlreadyDisplayBootBox = false;
                if (familyIndividualId == 0) {
                    $.each(memberIds, function(index, memberId) {
                        if (
                            $('#family-discipline-table [data-individual-id=' + memberId + ']').length < 1 &&
                            $('#family-discipline-table tbody tr[data-individual-id=0]').length == 1
                        ) {
                            bootbox.alert("Members must have at least one discipline assigned.");

                            isAlreadyDisplayBootBox = true;
                            return false;
                        }
                    });
                }

                if (isAlreadyDisplayBootBox) {
                    return false;
                }

                bootbox.confirm({
                    size: "medium",
                    message: "Are you sure you want to remove this discipline?",
                    callback: function(result) {
                        if (result === true) {
                            $('#reset-family-disciplines').show();
                            disciplineRow.remove();

                            updateRenewalPrice();
                        }
                    }
                });
            });

            $('#family-member-table').on('click', '.remove-family-member', function() {
                var row = this;
                var memberId = $(row).parent().parent().find('.family-member-id').val();

                if (currentIndividual.id == memberId) {
                    bootbox.alert("You cannot remove yourself from your family.");

                    return;
                }

                if ($('#family-member-table tbody tr').length == 2) {
                    bootbox.alert("Removing this user will leave only you renewing. If you wish to renew just on your own, then return to the previous step and choose Adult.");

                    return;
                }

                bootbox.confirm({
                    size: "medium",
                    message: "Are you sure you want to remove this family member?",
                    callback: function(result) {
                        if (result === true) {

                            memberIds = memberIds.filter(function(elementId) {
                                return elementId !== parseInt(memberId);
                            });

                            $(row).parent().parent().remove();

                            if ($('#family-member-table input[data-is-no-discipline=1]').length < 1) {
                                isFamilyWithoutDisciplines = 0;

                                $('.wizard-footer .pull-right').append(submitButton);
                                $('#family-discipline-table caption').addClass('hidden');
                            }

                            $('#reset-family-members').show();

                            $('#family-discipline-table tbody tr[data-individual-id=' + memberId + ']').remove();

                            delete fIndividualDisciplineIds[memberId];

                            $('#family-discipline-table tbody').html('');
                            setFamilyDisciplines(fIndividualDisciplineIds);

                            updateRenewalPrice();
                        }
                    }
                });
            });

            $('#family-discipline-table').on('click', '.edit-family-discipline', function() {
                var individualIds = $(this).parent().parent().find('.individual-id').val();
                individualIds = individualIds.split(",");

                var disciplineId = $(this).parent().parent().find('.discipline-id').val();
                var rowNumber = $(this).parent().parent().parent().attr('data-index');

                $('#edit-family-discipline-modal #disciplineModelTitle').html('Manage family members in ' + $(this).parent().parent().parent().find('.discipline-label').html());

                $('#edit-family-discipline-modal .modal-body').html(' ');
                $('#edit-family-discipline-modal #discipline-id').val(disciplineId);
                $('#edit-family-discipline-modal #discipline-row-number').val(rowNumber);

                $.each(individualIds, function(index, individualId) {
                    var individual = getIndividualDetails(individualId);
                    var checkbox = '';
                    if (
                        $('#family-discipline-table tbody .discipline-row[data-individual-id=0]').length == 1 &&
                        $('#family-discipline-table tbody .discipline-row[data-individual-id=' + individual.id + ']').length == 0
                    ) {
                        checkbox = 'disabled ';
                    }

                    var checkbox = '<label class="checkbox-inline"><input type="checkbox" class="individual-checkbox" id="individual-checkbox-' + individual.id + '" value="' + individual.id + '" ' + checkbox + 'checked> ' + individual.first_name + ' ' + individual.surname + '</label>';

                    $('#edit-family-discipline-modal .modal-body').append(checkbox);
                });

                $('#edit-family-discipline-modal').modal('show');
            });

            $('#edit-family-discipline-modal').on('click', '#update-family-discipline', function() {
                var disciplineRow = $('#edit-family-discipline-modal #discipline-row-number').val();
                var disciplineId = $('#edit-family-discipline-modal #discipline-id').val();

                var selectedCheckboxes = getCheckboxValue($('#edit-family-discipline-modal .individual-checkbox:checked'));

                if (JSON.stringify(familyIndividualIds) == JSON.stringify(selectedCheckboxes)) {
                    $('#edit-family-discipline-modal').modal('hide');
                    return;
                }

                $('#family-discipline-table tr.discipline-row[data-index=' + disciplineRow +']').remove();

                var loopIteration = parseInt($('#family-discipline-table tr').last().attr('data-index')) + 1;

                $.each(selectedCheckboxes, function(index, individualId) {
                    addFamilyDisciplineRow(disciplineId, loopIteration, individualId);

                    loopIteration++;
                });

                updateRenewalPrice();

                $('#reset-family-disciplines').show();
                $('#edit-family-discipline-modal').modal('hide');
            });
        });

        function setDisciplines() {
            $('#reset-disciplines').hide();

            $.each(disciplines, function(index, discipline) {
                $('#discipline-table tbody').append($('#discipline-template tbody').html());

                var disciplineRow = $('#discipline-table tbody tr.discipline-row').last();

                addDisciplineRow(disciplineRow, discipline);
            });
        }

        function setFamilyDisciplines(familyIndividualDisciplineIds) {
            $('#reset-family-disciplines').hide();
            var loopIteration = 0;
            allFamilyMemberDiscipline = getAllFamilyMemberDiscipline(familyIndividualDisciplineIds);

            // Set all family members discipline
            $.each(allFamilyMemberDiscipline, function(index, disciplineId) {
                addFamilyDisciplineRow(disciplineId, loopIteration, individualId = 0);

                loopIteration++;
            });

            // Set family members discipline
            $.each(familyIndividualDisciplineIds, function(individualId, disciplineIds) {

                $.each(disciplineIds, function(key, disciplineId) {
                    if (
                        $.inArray(disciplineId, allFamilyMemberDiscipline) == -1 &&
                        $.inArray(parseInt(individualId), memberIds) != -1
                    ) {
                        addFamilyDisciplineRow(disciplineId, loopIteration, individualId);

                        loopIteration++;
                    }
                });
            });
        }

        function addDisciplineRow(disciplineRow, discipline) {
            var disciplinePriceType = getDisciplinePriceType();

            $(disciplineRow).attr('data-id', discipline.id);
            $(disciplineRow).find('.discipline-label').html(discipline.label);
            $(disciplineRow).find('.discipline-price').html(discipline[disciplinePriceType]);
            $(disciplineRow).find('.discipline-id').val(discipline.id);
        }

        function addFamilyDisciplineRow(disciplineId, loopIteration, individualId) {
            var individual = '';
            var individualName = 'All family members';
            var individualIds = memberIds;

            if (individualId != 0) {
                individual = getIndividualDetails(individualId);
                individualName = individual.first_name + ' ' + individual.surname;
                individualIds = individual.id;
            }

            $('#family-discipline-table tbody').append($('#family-discipline-template tbody').html());
            var familyDisciplineRow = $('#family-discipline-table tbody tr.discipline-row').last();
            var discipline = getDisciplineDetails(disciplineId);

            var isPensioner = (individual && individual.pension_card && individual.membership.type_id == 3) ? 1 : 0;

            $(familyDisciplineRow).attr('data-id', discipline.id);
            $(familyDisciplineRow).attr('data-individual-id', individualId);
            $(familyDisciplineRow).attr('data-individual-ids', individualIds);
            $(familyDisciplineRow).attr('data-is-pensioner', isPensioner);
            $(familyDisciplineRow).attr('data-index', loopIteration);
            $(familyDisciplineRow).find('.discipline-label').html(discipline.label + ' - (' + individualName + ')');

            var disciplinePrice = getFamilyDisciplinePrice(discipline, individualId, isPensioner);

            $(familyDisciplineRow).find('.discipline-price').html(disciplinePrice);
            $(familyDisciplineRow).find('.discipline-id').val(discipline.id);
            $(familyDisciplineRow).find('.individual-id').val(individualIds);

            if (individualId == 0) {
                $(familyDisciplineRow).find('.action-container').append('<i class="fa fa-pencil-square edit-family-discipline pointer" aria-hidden="true"></i>');
            }
        }

        function getCheckboxValue(checkedCheckbox) {
            var values = [];

            $.each($(checkedCheckbox), function(index, checkbox) {
                values.push(parseInt($(checkbox).val()));
            });

            return values;
        }

        {{-- Returns the ids of the common disciplines --}}
        function getAllFamilyMemberDiscipline(familyIndividualDisciplineIds) {
            arrays = copyObject(familyIndividualDisciplineIds)

            {{-- https://codereview.stackexchange.com/a/97003 --}}
            var currentValues = {};
            var commonValues = {};
            for (var i = arrays[0].length - 1; i >= 0; i--) { //Iterating backwards for efficiency
                currentValues[arrays[0][i]] = 1; //Doesn't really matter what we set it to
            }

            for (var i = arrays.length - 1; i > 0; i--) {
                var currentArray = arrays[i];
                for (var j = currentArray.length - 1; j >= 0; j--) {
                    if (currentArray[j] in currentValues) {
                        commonValues[currentArray[j]] = 1; //Once again, the `1` doesn't matter
                    }
                }
                currentValues = commonValues;
                commonValues = {};
            }

            return Object.keys(currentValues).map(function (value) {
                return parseInt(value);
            });
        }
    </script>
@endpush
