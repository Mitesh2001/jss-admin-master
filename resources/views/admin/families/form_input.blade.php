<div class="col-12 mb-5">
    <div class="card">
        <div class="card-body p-3" id="family-container">
            <h5>Family Details</h5>

            <div class="form-group">
                <label for="individuals-select-box">Select Individual:</label>

                <select id="individuals-select-box"
                    class="form-control selectpicker"
                >
                    <option value="">Select individual to add to the list</option>

                    @foreach ($individuals as $individual)
                        <option value="{{ $individual->id }}">
                            {{ $individual->getName() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <ul class="list-group mt-5" id="individuals-list"></ul>
        </div>
    </div>
</div>

<template id="individual-row-template">
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <input type="hidden" name="individual_id[]" class="individual-id-hidden">

        <span class="individual-name"></span>

        <span class="">
            <button type="button"
                class="btn btn-link text-dark remove-individual"
                data-id=""
            >
                <i class="fa fa-trash" aria-hidden="true"></i>
            </button>
        </span>
    </li>
</template>

@push('scripts')
    <script>
        $('#individuals-select-box').selectpicker({ liveSearch: true, size: 5 });
        var individualIds = @json($family->individual_ids) || [];
        var individualsList = @json($family->individuals);

        $(document).ready(function () {
            $.each(individualsList, function(index, individual) {
                addIndividualRow(individual.id, individual.first_name + ' ' + individual.surname)
            });

            $('#family-container').on('click', 'button.remove-individual', function() {
                var individualId = $(this).data('id');
                $('#individual-' + individualId).remove();

                individualIds.splice(individualIds.indexOf(individualId), 1);
            });

            $('#individuals-select-box').change(function() {
                var individualId = parseInt($("#individuals-select-box option:selected").val());
                var individualName = $("#individuals-select-box option:selected").text();

                if (
                    individualId &&
                    individualIds.indexOf(individualId) < 0
                ) {
                    individualIds.push(individualId);

                    addIndividualRow(individualId, individualName);
                }

                $("#individuals-select-box option:nth(0)").prop("selected", "selected");
                $('#individuals-select-box').selectpicker('refresh');
            });
        });

        function addIndividualRow(individualId, individualName) {
            $('#individuals-list').prepend($('#individual-row-template').html());

            var row = $('#individuals-list li').first();
            $(row).attr('id', 'individual-' + individualId)
            $(row).find('.individual-name').html(individualName)
            $(row).find('.remove-individual').attr('data-id', individualId);
            $(row).find('.individual-id-hidden').attr('value', individualId);
        }
    </script>
@endpush
