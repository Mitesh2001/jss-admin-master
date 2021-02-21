<div class="col-12 col-md-6 mb-5">
    <div class="card">
        <div class="card-body p-3">
            <h5>Individual Details</h5>

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="fore-name">Forename:</label>
                        <input type="text"
                            class="form-control"
                            id="fore-name"
                            name="first_name"
                            value="{{ old('first_name', $individual->first_name) }}"
                        >
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="middle-name">Middle Name:</label>
                        <input type="text"
                            class="form-control"
                            id="middle-name"
                            name="middle_name"
                            value="{{ old('middle_name', $individual->middle_name) }}"
                        >
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="surname">Surname:</label>
                        <input type="text"
                            class="form-control"
                            id="surname"
                            name="surname"
                            value="{{ old('surname', $individual->surname) }}"
                        >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <label for="dob">DOB:</label>

                        <input id="dob"
                            type="date"
                            name="date_of_birth"
                            value="{{ old('date_of_birth', $individual->date_of_birth) }}"
                            class="form-control"
                            data-input
                        >

                        <div class="input-group-prepend">
                            <a class="input-group-text" id="btnGroupAddon" data-toggle>
                                <i class="fa fa-calendar"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="gender">Gender:</label>

                        <select id="gender" name="gender_id" class="form-control">
                            <option value="">Please select gender</option>

                            @foreach ($genders as $gender)
                                <option value="{{ $gender->id }}"
                                    {{ old('gender_id', $individual->gender_id) == $gender->id ? 'selected' : '' }}
                                >
                                    {{ $gender->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="occupation">Occupation:</label>
                        <input id="occupation"
                            class="form-control"
                            name="occupation"
                            value="{{ old('occupation', $individual->occupation) }}"
                        >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="email-address">Email:</label>
                        <input type="email"
                            id="email-address"
                            name="email_address"
                            class="form-control"
                            value="{{ old('email_address', $individual->email_address) }}"
                        >
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="phone-number">Phone Number:</label>

                        <input type="text"
                            id="phone-number"
                            name="phone_number"
                            class="form-control"
                            value="{{ old('phone_number', $individual->phone_number) }}"
                        >
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="mobile-number">Mobile Number:</label>

                        <input type="text"
                            id="mobile-number"
                            name="mobile_number"
                            class="form-control"
                            value="{{ old('mobile_number', $individual->mobile_number) }}"
                        >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <label for="address">Address:</label>
                    <div class="form-group">
                        <input id="address"
                            class="form-control mb-4"
                            name="address_line_1"
                            placeholder="Line 1"
                            value="{{ old('address_line_1', $individual->address_line_1) }}"
                        >
                        <input class="form-control mb-4"
                            name="address_line_2"
                            placeholder="Line 2"
                            value="{{ old('address_line_2', $individual->address_line_2) }}"
                        >
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="hidden" name="pension_card" value="0">

                            <input type="checkbox"
                                class="custom-control-input"
                                value="1"
                                id="pension-card-confirmed"
                                name="pension_card"

                                {{ old('pension_card', $individual->pension_card) ? 'checked' : '' }}
                            >

                            <label class="custom-control-label" for="pension-card-confirmed">
                                Pension Card Confirmed
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="hidden" name="is_committee_member" value="0">

                            <input type="checkbox"
                                class="custom-control-input"
                                value="1"
                                id="is-committee-member"
                                name="is_committee_member"

                                {{ old('is_committee_member', $individual->is_committee_member) ? 'checked' : '' }}
                            >

                            <label class="custom-control-label" for="is-committee-member">
                                Is Current Committee Member?
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="hidden" name="is_club_lifetime_member" value="0">

                            <input type="checkbox"
                                class="custom-control-input"
                                value="1"
                                id="is-club-lifetime-member"
                                name="is_club_lifetime_member"

                                {{ old('is_club_lifetime_member', $individual->is_club_lifetime_member) ? 'checked' : '' }}
                            >

                            <label class="custom-control-label" for="is-club-lifetime-member">
                                Is Club Lifetime Member?
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="suburb">Suburb:</label>
                        <select id="suburb" name="suburb_id" class="form-control"></select>
                    </div>

                    <div class="form-group">
                        <label for="state">State:</label>

                        <select id="state" name="state_id" class="form-control">
                            <option value="">Please select a state</option>

                            @foreach ($states as $state)
                                <option value="{{ $state->id }}"
                                    {{ old('state_id', $individual->state_id) == $state->id ? 'selected' : '' }}
                                >
                                    {{ $state->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="postcode">Postcode:</label>
                        <input type="text"
                            id="postcode"
                            name="post_code"
                            class="form-control"
                            onkeypress="return isNumber(event, this, isPostcode = true)"
                            value="{{ old('post_code', $individual->post_code) }}"
                        >
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="wwc-card-number">WWC Card Number:</label>

                        <input type="text"
                            id="wwc-card-number"
                            name="wwc_card_number"
                            class="form-control"
                            onkeypress="return isNumber(event, this) || event.keyCode == 45"
                            value="{{ old('wwc_card_number', $individual->wwc_card_number) }}"
                        >
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <label for="wwc-expiry-date">WWC Expiry Date:</label>

                    <div class="input-group" id="date-picker-container">
                        <input type="text"
                            id="wwc-expiry-date"
                            class="form-control w-unset"
                            name="wwc_expiry_date"
                            value="{{ old('wwc_expiry_date', $individual->wwc_expiry_date) }}"
                            data-input
                        >

                        <div class="input-group-append pointer">
                            <span class="input-group-text" data-toggle>
                                <i class="fa fa-calendar"></i>
                            </span>

                            <span class="input-group-text pointer" data-clear>
                                <i class="fa fa-close text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <h5>SSAA</h5>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="ssaa-number">SSAA Number:</label>

                        <input type="text"
                            id="ssaa-number"
                            name="ssaa_number"
                            class="form-control"
                            value="{{ old('ssaa_number', optional($individual->ssaa)->ssaa_number) }}"
                            required
                        >
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="branch-code">Branch Code:</label>

                        <select id="branch-code" name="branch_code_id" class="form-control" required>
                            <option value="">Please select a branch code</option>

                            @foreach ($branchCodes as $branchCode)
                                <option value="{{ $branchCode->id }}"
                                    {{ old('branch_code_id', $individual->branch_code_id) == $branchCode->id ? 'selected' : '' }}
                                >
                                    {{ $branchCode->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="ssaa-status">SSAA Status:</label>

                        <select id="ssaa-status" name="ssaa_status" class="form-control" required>
                            <option value="" selected>Please select SSAA status</option>

                            <option value="1" {{ old('ssaa_status', optional($individual->ssaa)->ssaa_status) == 1 ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ old('ssaa_status', optional($individual->ssaa)->ssaa_status) === 0 ? 'selected' : '' }}
                            >
                                Inactive
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <label for="ssaa-expiry">SSAA Expiry:</label>

                        <input type="date"
                            id="ssaa-expiry"
                            class="form-control"
                            name="ssaa_expiry"
                            value="{{ old('ssaa_expiry', optional($individual->ssaa)->ssaa_expiry) }}"
                            data-input
                            required
                        >

                        <div class="input-group-prepend">
                            <a class="input-group-text" id="btnGroupAddon" data-toggle>
                                <i class="fa fa-calendar"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var selectedSuburbId = "{{ old('suburb_id') ?? '' }}";
        $('#date-picker-container').flatpickr({
            wrap: true
        });

        if (! selectedSuburbId) {
            selectedSuburbId = "{{ $individual->suburb_id ?? '' }}";
        }

        var displayWarning = true;

        $('#ssaa-number').on('keypress', function () {
            $('#membership-number').val('W02 ' + $(this).val())
        });

        $('#add-individual-form').on('submit', function (event) {
            if (! displayWarning ||
                ($('#mobile-number').val() && $('#email-address').val())
            ) {
                return;
            }

            var form = $(this);
            event.preventDefault();

            bootbox.confirm({
                size: "medium",
                message: "You have not entered an email address or mobile for this individual, this is highly recommended.",
                callback: function(result) {
                    if (result === true) {
                        displayWarning = false;
                        $(form).submit();
                    }
                }
            });
        });
    </script>
@endpush

@include('common.suburb_bootstrap_select')
