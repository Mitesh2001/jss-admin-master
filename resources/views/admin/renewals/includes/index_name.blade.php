{{
    $individualRenewal->first_name . ' ' . $individualRenewal->surname
}}

@if($individualRenewal->payment_type == 1 || optional($individualRenewal->parentRenewal)->payment_type == 1)
    <i class="fa fa-circle text-danger" aria-hidden="true" title="Offline Payment"></i>
@elseif($individualRenewal->payment_type == 2 || optional($individualRenewal->parentRenewal)->payment_type == 2)
    <i class="fa fa-circle text-success" aria-hidden="true" title="Online Payment"></i>
@endif

{!! $individualRenewal->renewal->confirmation_emailed || optional(optional($individualRenewal->parentRenewal)->renewal)->confirmation_emailed ? '<i class="fa fa-check text-success" title="Complete Renewal"></i>' : '<i class="fa fa-times text-danger" title="Incomplete Renewal"></i>' !!}
