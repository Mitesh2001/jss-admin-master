<div class="modal fade" id="renewal-emails-modal" tabindex="-1" role="dialog" aria-labelledby="renewal-emails-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="renewal-emails-modal-title">Send Renewal Emails</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form method="post" action="{{ route('admin.send_renewals_emails') }}" id="send-renewal-email-form">
                    @csrf

                    <input type="hidden" name="renewal_run_id" value="{{ $renewalRun->id }}">
                    <input type="hidden" id="individual-id" name="individual_id" value="">
                    <input type="hidden" id="is-reminder-email" name="is_reminder_email" value="0">

                    <div class="row">
                        <div class="col">
                            <p id="renewal-to-all">
                                You are about to send renewal emails to
                                <strong>{{ optional($renewalRun)->entities_count }} active</strong>
                                individuals.
                            </p>

                            <p id="renewal-to-one" class="d-none">
                                You are about to send a renewal email to <strong id="renewal-email-individual-name"></strong>.
                            </p>

                            <p id="reminder-to-all" class="d-none">
                                You are about to send reminder emails to
                                <strong>{{ optional($renewalRun)->entities_count - optional($renewalRun)->submitted_count }} pending</strong>
                                individuals.
                            </p>

                            <p id="reminder-to-one" class="d-none">
                                You are about to send a reminder email to <strong id="reminder-email-individual-name"></strong>.
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group custom-control custom-checkbox">
                                <input type="hidden" name="send_to_test_email" value="0">
                                <input type="checkbox"
                                    name="send_to_test_email"
                                    class="custom-control-input"
                                    id="send-to-test-email"
                                    value="1"
                                >
                                <label class="custom-control-label" for="send-to-test-email">
                                    Send to a test email address instead
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row d-none" id="test-email-container">
                        <div class="col">
                            <div class="form-group">
                                <label for="test-email">Test Email Address</label>
                                <input type="email" class="form-control" id="test-email" aria-describedby="renewal-email-help" name="test_email">
                                <small id="renewal-email-help" class="form-text text-muted">
                                    We will send maximum of 20 renewal emails to this email address.
                                </small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="send-emails" class="btn btn-primary">Send</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $( document ).ready(function() {
        $('input[name=send_to_test_email]').change(function() {
            if (this.checked) {
                $('#test-email-container').removeClass('d-none');

                return;
            }

            $('#test-email-container').addClass('d-none');
        });
    });

    $('#renewal-emails-modal').on('hidden.bs.modal', function (e) {
        $('#individual-id').val('');
        $('#is-reminder-email').val(0);
        $('#renewal-to-all').removeClass('d-none');
        $('#renewal-to-one').addClass('d-none');
        $('#reminder-to-all').addClass('d-none');
        $('#reminder-to-one').addClass('d-none');
        $('#renewal-email-help').removeClass('d-none');
    });

    $('#send-emails').click(function() {
        if ($('#send-to-test-email').is(":checked") && $('#test-email').val() == '') {
            alert('Please specify Test Email Address.');
            $("#test-email").focus();

            return;
        }

        $(this).attr('disabled', true);

        $.ajax({
            method: "POST",
            url: $('#send-renewal-email-form').attr('action'),
            data: $("#send-renewal-email-form").serialize()
        }).done(function( response ) {
            showNotice(response.type, response.message);

            $('#send-emails').attr('disabled', false);

            $('#renewal-emails-modal').modal('hide');
        });
    });

    function openRenewalModalPopup(individualId) {
        $('#individual-id').val(individualId);

        $('#renewal-to-all').addClass('d-none');
        $('#renewal-email-help').addClass('d-none');

        $('#renewal-emails-modal').modal('show');
    }
</script>
@endpush
