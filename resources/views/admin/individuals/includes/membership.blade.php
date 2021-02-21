<h5>Membership Details</h5>

<div class="row">
    <div class="col-12 col-md-6">
        <div class="form-group mb-2">
            <label for="membership-number">Membership #:</label>

            <input type="text"
                class="form-control"
                name="membership_number"
                id="membership-number"
                value="{{ $membershipNumber }}"
                readonly
            >
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group">
            <label for="join-date">Membership Join Date:</label>

            <input type="date"
                id="join-date"
                class="form-control"
                name="join_date"
                value="{{ old('join_date', $individualMembership->join_date) ?: now()->format('Y-m-d') }}"
                data-input
            >

            <div class="input-group-prepend">
                <a class="input-group-text" id="btnGroupAddon" data-toggle>
                    <i class="fa fa-calendar"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6">
        <div class="form-group">
            <label for="membership-status">Membership Status:</label>

            <select id="membership-status"
                name="membership_status"
                class="form-control"
                data-supported-firearm-count="{{ $individual->supported_firearms_count }}"
                data-status="{{ $individualMembership->status }}"
            >
                <option value="1" selected>Active</option>

                <option value="0"
                    {{ old('membership_status', $individualMembership->status) == 0 ? 'selected' : '' }}
                >
                    Inactive
                </option>
            </select>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-group">
            <label for="membership-type">Membership Type:</label>

            <select id="membership-type" name="membership_type_id" class="form-control">
                @foreach ($membershipTypes as $membershipType)
                    <option value="{{ $membershipType->id }}"
                        {{ old('membership_type_id', $individualMembership->type_id) == $membershipType->id ? 'selected' : '' }}
                    >
                        {{ $membershipType->label }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6">
        <div class="input-group">
            <label for="membership-expiry">Membership Expiry:</label>

            <input type="date"
                id="membership-expiry"
                name="expiry"
                class="form-control"
                value="{{ old('expiry', $individualMembership->expiry) ?: now()->endOfYear()->format('Y-m-d') }}"
                data-input
            >

            <div class="input-group-prepend">
                <a class="input-group-text" id="btnGroupAddon" data-toggle>
                    <i class="fa fa-calendar"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="row">
            <div class="col-12 col-md-6">
                Card ID
            </div>
            <div class="col-12 col-md-6">
                PrintDate
            </div>
        </div>

        @php
            $idCard = optional(optional(optional($individual)->idCards)
                ->where('printed_at', '!=', null)
                ->sortBy('printed_at'))
                ->first()
            ;
        @endphp

        @if (! $idCard)
            <h5 class="text-center mt-2">No Records Found.</h5>
        @else
            <div class="row mt-2">
                <div class="col-12 col-md-4">
                    {{ $idCard->id }}
                </div>

                <div class="col-12 col-md-8">
                    {{ $idCard->printed_at }}
                </div>
            </div>
        @endif

    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="form-group">
            <label for="notes">Membership Notes:</label>

            <textarea class="form-control"
                name="notes"
                id="notes"
                rows="3"
                placeholder="Notes"
            >{{ old('notes', $individualMembership->notes) }}</textarea>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        $('#membership-status').change(function () {
            if (
                $(this).attr('data-status') == 1 &&
                $(this).attr('data-supported-firearm-count') != 0 &&
                $(this).val() == 0
            ) {
                alert('This member has '+$(this).attr('data-supported-firearm-count')+' supported firearms, be sure to remove support for these if necessary.');
            }
        });
    </script>
@endpush
