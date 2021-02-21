<div class="row">
    <div class="col-12 col-md-6">
        <div class="form-group">
            <label for="period-year">Period:</label>

            <select id="period-year" name="period" class="form-control" required>
                <option value="">Please select a period year</option>

                @for ($i = -2; $i <= 5; $i++)
                    @php
                        $year = $currentYear + $i;
                    @endphp

                    <option value="{{ $year }}"
                        {{ old('period', $renewalRun->period) == $year ? 'selected' : '' }}
                    >{{ $year }}</option>
                @endfor
            </select>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group">
            <label for="start-date">Start Date:</label>

            <input type="date"
                class="form-control"
                id="start-date"
                name="start_date"
                value="{{ old('start_date', $renewalRun->start_date) }}"
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

    <div class="col-12 col-md-6">
        <div class="input-group">
            <label for="payment-due-date">Payment Due Date:</label>

            <input type="date"
                class="form-control"
                id="payment-due-date"
                name="payment_due_date"
                value="{{ old('payment_due_date', $renewalRun->payment_due_date) }}"
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

    <div class="col-12 col-md-6">
        <div class="input-group">
            <label for="expiry-date">Expiry Date:</label>

            <input type="date"
                class="form-control"
                id="expiry-date"
                name="expiry_date"
                value="{{ old('expiry_date', $renewalRun->expiry_date) }}"
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


@push('scripts')
    <script>
        $('#period-year').change(function () {
            if (! $(this).val()) {
                return;
            }

            var date = $(this).val() + '-' + '3-31';

            document.querySelector("#payment-due-date")._flatpickr.setDate(date);
            document.querySelector("#expiry-date")._flatpickr.setDate(date);
            date = $(this).val() + '-' + '1-01';
            document.querySelector("#start-date")._flatpickr.setDate(date);
        });
    </script>
@endpush
