<div class="tab-pane" id="individual-details">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="info-text">
                {{ config('app.name') }}<br>
                Renewal of Membership -
                {{ $renewalRun->period }}
            </h3>
        </div>

        <div class="col-xs-12 col-md-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="material-icons"><i class="fa fa-user"></i></i>
                </span>

                <div class="form-group label-floating">
                    <label class="control-label">First Name</label>

                    <input type="text"
                        class="form-control"
                        value="{{ $individual->first_name }}"
                        readonly
                    >
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="material-icons"><i class="fa fa-user"></i></i>
                </span>

                <div class="form-group label-floating">
                    <label class="control-label">Middle Name</label>

                    <input type="text"
                        class="form-control"
                        value="{{ $individual->middle_name }}"
                        readonly
                    >
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="material-icons"><i class="fa fa-user"></i></i>
                </span>

                <div class="form-group label-floating">
                    <label class="control-label">Surname</label>

                    <input type="text"
                        class="form-control"
                        value="{{ $individual->surname }}"
                        readonly
                    >
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-6">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="material-icons"><i class="fa fa-envelope"></i></i>
                </span>

                <div class="form-group label-floating">
                    <label class="control-label">Email Address</label>

                    <input type="text"
                        class="form-control"
                        value="{{ $individual->email_address }}"
                        readonly
                    >
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-6">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="material-icons"><i class="fa fa-calendar"></i></i>
                </span>

                <div class="form-group label-floating">
                    <label class="control-label">
                        Date of birth
                    </label>

                    <input type="text"
                        value="{{ date('jS F Y', strtotime($individual->date_of_birth)) }}"
                        class="form-control"
                        readonly
                    >
                </div>
            </div>
        </div>

        <div class="col-xs-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-transgender-alt"></i>
                </span>

                <div class="form-group label-floating" id="suburb-input">
                    <select id="gender" name="gender_id" class="form-control" required>
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
        </div>

        <div class="col-xs-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="material-icons"><i class="fa fa-phone"></i></i>
                </span>

                <div class="form-group label-floating">
                    <label class="control-label">Mobile</label>

                    <input type="text"
                        name="mobile_number"
                        value="{{ $individual->mobile_number }}"
                        class="form-control"
                        onkeypress="return isNumber(event, this, isPostcode = false)"
                        required
                    >
                </div>
            </div>
        </div>

        <div class="col-xs-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="material-icons"><i class="fa fa-phone"></i></i>
                </span>

                <div class="form-group label-floating">
                    <label class="control-label">Phone</label>

                    <input type="text"
                        name="phone_number"
                        class="form-control"
                        value="{{ $individual->phone_number }}"
                        onkeypress="return isNumber(event, this, isPostcode = false)"
                    >
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="address-wrapper">

                <div class="form-group">
                    <input type="text"
                        name="address_line_1"
                        value="{{ $individual->address_line_1 }}"
                        class="form-control"
                        placeholder="Address"
                        required
                    >
                </div>

                <div class="form-group">
                    <input type="text"
                        name="address_line_2"
                        value="{{ $individual->address_line_2 }}"
                        class="form-control"
                    >
                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-university"></i>
                        </span>

                        <div class="form-group label-floating" id="suburb-input">
                            <select id="suburb"
                                name="suburb_id"
                                class="form-control"
                                required
                            ></select>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-university"></i>
                        </span>

                        <div class="form-group label-floating">
                            <?php $stateId = old('state_id', $individual->state_id) ?? 4; ?>

                            <select id="state" name="state_id" class="form-control" required>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ $stateId == $state->id ? 'selected' : '' }}
                                    >
                                        {{ $state->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-pencil-square-o"></i>
                        </span>

                        <div class="form-group label-floating">
                            <input type="text"
                                name="post_code"
                                value="{{ $individual->post_code }}"
                                name="text"
                                class="form-control"
                                placeholder="Postal Code"
                                onkeypress="return isNumber(event, this, isPostCode = true)"
                                required
                            >
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var selectedSuburbId = "{{ $individual->suburb_id ?? '' }}";
    </script>
@endpush

@include('common.suburb_bootstrap_select')
