<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle remove-down-icon" type="button" data-toggle="dropdown">
        <i class="fa fa-ellipsis-h"></i>
    </button>

    <div class="dropdown-menu">
        @if ($individualRenewal->payment_type == 1)
            <a class="dropdown-item pointer"
                data-toggle="modal"
                data-target="#process-renewal-modal"
                data-approved="{{ $individualRenewal->renewal->approved }}"
                data-individual-renewal-id="{{ $individualRenewal->id }}"
                data-url="{{ route('admin.individual_renewals.process_renewal', ['individualRenewal' => $individualRenewal->id]) }}"
                data-email="{{ $individualRenewal->email_address }}"
                data-outstanding-amount="{{ $individualRenewal->renewal->getOutstandingAmount() }}"
            >
                <i class="fa fa-cog"></i>
                Process Renewal
            </a>
        @endif

        <a class="dropdown-item"
            href="mailto:{{ $individualRenewal->email_address }}"
        >
            <i class="fa fa-envelope"></i> Outlook Mail
        </a>
    </div>
</div>
