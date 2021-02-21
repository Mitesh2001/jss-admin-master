@extends('admin.layouts.app', ['page' => 'renewal_runs'])

@section('title', 'Renewal Run Details')

@push('styles')
@include('common.datatables.styles')
@endpush

@section('content')
<div class="row">
    <div class="col-3 h3" style="color: #5a5b5d;">
        Renewal Run Details
    </div>

    <div class="col-9 text-right header-form-controls">
        <button class="btn btn-info active btn-sm" id="send-renewal-emails-to-all">
            @if ($renewalRun->emails_count)
                Send Reminder Emails to Pending
            @else
                Send Renewal Emails to All
            @endif
        </button>

        <div class="form-group d-inline-block">
            <select id="submitted-filter" class="form-control">
                <option value="all">Submitted &amp; Unsubmitted</option>

                <option value="submitted_only"
                    {{ session('renewals_run_details_filter') == 'submitted_only' ? 'selected' : '' }}
                >
                    Submitted Only
                </option>

                <option value="unsubmitted_only"
                    {{ session('renewals_run_details_filter') == 'unsubmitted_only' ? 'selected' : '' }}
                >
                    Unsubmitted Only
                </option>

            </select>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive m-2">
        <table class="table table-striped table-hover" id="renewal-run-details-table">
            <thead>
                <tr>
                    <th>Membership #</th>
                    <th>Individual Name</th>
                    <th>Status</th>
                    <th>Email Last Sent</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($renewalRunEntities as $renewalRunEntity)
                    <tr>
                        <td>{{ $renewalRunEntity->membership_number }}</td>
                        <td>{{ $renewalRunEntity->first_name . ' ' . $renewalRunEntity->surname }}</td>
                        <td>{{ $renewalRunEntity->status_label }}</td>
                        <td>{{ $renewalRunEntity->email_last_sent_at }}</td>
                        <td>
                            @if (in_array($renewalRunEntity->status_label, ['Not Sent', 'Not Submitted']))
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle remove-down-icon" type="button" data-toggle="dropdown">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </button>

                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ getRenewalLink($renewalRunEntity->individual_id, $renewalRun->id) }}"
                                            target="_blank"
                                        >
                                            <i class="fa fa-refresh"></i> Renewal Link
                                        </a>

                                        <a class="dropdown-item send-renewal-email pointer"
                                            data-individual-name="{{ $renewalRunEntity->first_name . ' ' . $renewalRunEntity->surname }}"
                                            data-individual-id="{{ $renewalRunEntity->individual_id }}"
                                        >
                                            <i class="fa fa-envelope"></i> Send Renewal Email
                                        </a>

                                        <a class="dropdown-item send-reminder-email pointer"
                                            data-individual-name="{{ $renewalRunEntity->first_name . ' ' . $renewalRunEntity->surname }}"
                                            data-individual-id="{{ $renewalRunEntity->individual_id }}"
                                        >
                                            <i class="fa fa-envelope"></i> Send Reminder Email
                                        </a>

                                        <a class="dropdown-item remove-from-renewal-run pointer"
                                            data-renewal-run-id="{{ $renewalRunEntity->renewal_run_id }}"
                                            data-individual-id="{{ $renewalRunEntity->individual_id }}"
                                        >
                                            <i class="fa fa-trash"></i> Remove from Renewal Run
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('admin.renewal_runs.renewal_emails_modal')

@endsection

@push('scripts')
@include('common.datatables.scripts')

<script>
    var isReminderEmail = {{ $renewalRun->emails_count ? 'true' : 'false' }};

    $( document ).ready(function() {
        $('#renewal-run-details-table').DataTable({
            responsive: true
        });

        $('#submitted-filter').change(function(e) {
            window.location.href = "{{ route('admin.renewal_runs.submitted_filter', ['renewalRun' => $renewalRun->id]) }}/" + $(this).val();
        });

        $('#renewal-run-details-table').on('click', 'a.remove-from-renewal-run', function(e) {
            e.preventDefault();
            var renewalRunId = $(this).data('renewalRunId');
            var individualId = $(this).data('individualId');

            bootbox.confirm({
                size: "medium",
                message: "Do you want to remove this individual from the renewal run?",
                callback: function(result) {
                    if (result === true) {
                        $.ajax({
                            method: "GET",
                            url: "{{ route('admin.remove_from_renewal_run') }}/" + renewalRunId + "/" + individualId
                        }).done(function() {
                            showNotice('success', 'Individual removed from the renewal run successfully.');

                            setTimeout(function(){
                                window.location.reload();
                            }, 2000);
                        });
                    }
                }
            });
        });

        $('#send-renewal-emails-to-all').click(function(e) {
            if (isReminderEmail) {
                $('#is-reminder-email').val(1);
                $('#renewal-to-all').addClass('d-none');
                $('#reminder-to-all').removeClass('d-none');
            }

            $('#renewal-emails-modal').modal('show');
        });

        $('#renewal-run-details-table').on('click', 'a.send-renewal-email', function(e) {
            e.preventDefault();

            $('#renewal-email-individual-name').html($(this).data('individualName'));
            $('#renewal-to-one').removeClass('d-none');

            openRenewalModalPopup($(this).data('individualId'));
        });

        $('#renewal-run-details-table').on('click', 'a.send-reminder-email', function(e) {
            e.preventDefault();

            $('#is-reminder-email').val(1);
            $('#reminder-email-individual-name').html($(this).data('individualName'));
            $('#reminder-to-one').removeClass('d-none');

            openRenewalModalPopup($(this).data('individualId'));
        });
    });
</script>
@endpush
