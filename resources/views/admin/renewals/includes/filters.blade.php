<div class="form-group d-inline-block">
    <select id="filter" class="form-control">
        <option value="all">Complete &amp; Incomplete</option>

        <option value="incomplete_only"
            {{ ! session('individual_renewals_filter') || session('individual_renewals_filter') == 'incomplete_only' ? 'selected' : '' }}
        >
            Incomplete Only
        </option>

        <option value="complete_only"
            {{ session('individual_renewals_filter') == 'complete_only' ? 'selected' : '' }}
        >
            Complete Only
        </option>

    </select>
</div>


<div class="form-group d-inline-block">
    <select id="payment-type-filter" class="form-control">
        <option value="all">Online &amp; Offline</option>

        <option value="online_only"
            {{ session('individual_renewals_payment_type_filter') == 'online_only' ? 'selected' : '' }}
        >
            Online Only
        </option>

        <option value="offline_only"
            {{ session('individual_renewals_payment_type_filter') == 'offline_only' ? 'selected' : '' }}
        >
            Offline Only
        </option>
    </select>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#filter').change(function(e) {
                window.location.href = "{{ route('admin.individual_renewals_filter') }}/" + $(this).val();
            });

            $('#payment-type-filter').change(function(e) {
                window.location.href = "{{ route('admin.individual_renewals_payment_type_filter') }}/" + $(this).val();
            });
        });
    </script>
@endpush
