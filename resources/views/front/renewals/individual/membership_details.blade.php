<div class="tab-pane" id="membership-details">
    <input type="hidden" name="is_family_renewal_already_paid" value="{{ $isFamilyRenewalAlreadyPaid }}">
    <div>
        <div class="col-xs-12 col-md-6">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1">
                    <i class="material-icons"><i class="fa fa-pencil-square-o"></i></i>
                </span>

                <div class="form-group label-floating">
                    <label class="control-label">JSS Membership Number</label>

                    <input type="text"
                        value="{{ $individual->getMembershipNumber() }}"
                        class="form-control"
                        readonly
                    >
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-6">
            <div class="input-group ssaa-expiry">
                <span class="input-group-addon" onclick="openCalendar()">
                    <i class="material-icons"></i>
                    <i class="fa fa-calendar"></i>
                </span>

                <label class="control-label">SSAA Membership Expiry</label>

                <div class="form-group label-floating m-0">
                    <input type="date"
                        id="ssaa-expiry"
                        name="ssaa_expiry"
                        class="form-control"
                        value="{{ old('ssaa_expiry') }}"
                        required
                    >
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <blockquote class="text-center">
                <p>
                    Current membership type:
                    <strong>
                        @if($isFamilyRenewalAlreadyPaid)
                            Family
                            <input type="hidden" name="type_id" value="2">
                        @else
                            {{ $individual->getType() }}
                        @endif
                    </strong>
                </p>

                <div class="clearfix"></div>
            </blockquote>
        </div>
    </div>

    @if(! $isFamilyRenewalAlreadyPaid)
        <div class="col-sm-10 col-sm-offset-1">
            <h3 class="sub-heading">
                Do you wish to renew your membership as an adult or a family?
            </h3>
        </div>

        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                @if($individual->family_id)
                    <div class="col-sm-{{ $numberOfColumns }}" id="type-family">
                        <div class="choice type-choice{{ $individual->family_id || old('type_id', $currentTypeId) == 2 ? ' active' : '' }}" data-toggle="wizard-radio">
                            <input type="radio"
                                name="type_id"
                                value="2"
                                data-price="{{ $familyPrice }}"
                                {{ $individual->family_id || old('type_id', $currentTypeId) == 2 ? 'checked' : '' }}
                                required
                            >

                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>

                            <h6>Family</h6>
                        </div>
                    </div>
                @endif

                <div class="col-sm-{{ $numberOfColumns }}" id="type-adult">
                    <div class="choice type-choice{{ ! $individual->family_id && ! $individual->pension_card && old('type_id', $currentTypeId) == 1 ? ' active' : '' }}"
                        data-toggle="wizard-radio"
                    >
                        <input type="radio"
                            name="type_id"
                            value="1"
                            data-price="{{ $adultPrice }}"
                            {{ ! $individual->family_id && ! $individual->pension_card && old('type_id', $currentTypeId) == 1 ? 'checked' : '' }}
                            required
                        >

                        <div class="icon">
                            <i class="fa fa-user"></i>
                        </div>

                        <h6>Adult</h6>
                    </div>
                </div>

                @if($individual->pension_card)
                    <div class="col-sm-{{ $numberOfColumns }}" id="type-pensioner">
                        <div class="choice type-choice{{ (! $individual->family_id && $individual->pension_card) || old('type_id', $currentTypeId) == 3 ? ' active' : '' }}"
                            data-toggle="wizard-radio"
                        >
                            <input type="radio"
                                name="type_id"
                                value="3"
                                data-price="{{ $pensionerPrice }}"
                                {{ (! $individual->family_id && $individual->pension_card) || old('type_id', $currentTypeId) == 3 ? 'checked' : '' }}
                                required
                            >

                            <div class="icon">
                                <i class="fa fa-user"></i>
                            </div>

                            <h6>Pensioner</h6>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        var ssaaFlatpickr = '';

        $(document).ready(function() {
            updatesAsPerType($('input[name=type_id]:checked').val());

            ssaaFlatpickr = $("#ssaa-expiry").flatpickr({
                altInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
                minDate: "{{ $renewalRun->start_date }}"
            });

            $(document).on( "click", '[data-toggle="wizard-radio"]', function() {
                wizard = $(this).closest('.wizard-card');
                wizard.find('[data-toggle="wizard-radio"]').removeClass('active');
                $(this).addClass('active');
                $(wizard).find('[type="radio"]').prop('checked', false);
                $(this).find('[type="radio"]').prop('checked', true);

                updatesAsPerType($(this).find('input[name=type_id]').val());
            });
        });

        function openCalendar() {
            setTimeout(function(){
                ssaaFlatpickr.open();
            }, 0);
        }
    </script>
@endpush
