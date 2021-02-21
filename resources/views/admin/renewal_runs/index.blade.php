@extends('admin.layouts.app', ['page' => 'renewal_runs'])

@section('title', 'Renewal Runs')

@push('styles')
    @include('common.datatables.styles')
@endpush

@section('content')
<div class="row">
    <div class="col-6 h3" style="color: #5a5b5d;">
        Renewal Runs
    </div>

    <div class="col-6 text-right header-form-controls">
        <div class="form-group d-inline-block">
            <a class="btn btn-success btn-sm" href="{{ route('admin.renewal-runs.create') }}">
                Create
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body m-2">
        <table class="table table-striped table-hover" id="renewal-runs-table">
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Entities</th>
                    <th>Submitted</th>
                    <th>Processed</th>
                    <th>Emails Last Sent</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($renewalRuns as $renewalRun)
                    <tr>
                        <td>
                            {{ $renewalRun->period }}
                            <i class="fa fa-info-circle pointer ml-2"
                                data-toggle="popover"
                                data-trigger="hover"
                                data-html="true"
                                data-target-id="renewal-run-{{ $renewalRun->id }}"
                                data-title="Renewal Run Details"
                            ></i>

                            <div id="renewal-run-{{ $renewalRun->id }}" class="d-none">
                                <b>Start Date</b>:
                                {{ $renewalRun->start_date }}
                                <br>

                                <b>Payment Due Date</b>:
                                {{ $renewalRun->payment_due_date }}
                                <br>

                                <b>Expiry Date</b>:
                                {{ $renewalRun->expiry_date }}
                            </div>
                        </td>
                        <td>{{ $renewalRun->entities_count }}</td>
                        <td>{{ $renewalRun->submitted_count }}</td>
                        <td>{{ $renewalRun->processed_count }}</td>
                        <td>{{ $renewalRun->emails->isNotEmpty() ? $renewalRun->emails->first()->sent_at : 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $renewalRun->status ? 'success' : 'danger' }}">
                                {{ $renewalRun->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle remove-down-icon" type="button" data-toggle="dropdown">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>

                                <div class="dropdown-menu">
                                    <a class="dropdown-item"
                                        href="{{ route('admin.renewal_runs.details', ['renewalRun' => $renewalRun->id]) }}"
                                    >
                                        <i class="fa fa-address-book"></i> Details
                                    </a>

                                    @if ($renewalRun->entities_count == 0)
                                        <a class="dropdown-item add-active-individuals-to-this-run pointer"
                                            data-renewal-run-id="{{ $renewalRun->id }}"
                                        >
                                            <i class="fa fa-users"></i> Add Active Individuals to this run
                                        </a>
                                    @endif

                                    @if (! $renewalRun->status)
                                        <a class="dropdown-item pointer"
                                            href="{{ route('admin.renewal_runs.status', ['renewalRun' => $renewalRun->id]) }}"
                                        >
                                            <i class="fa fa-check"></i> Make Active
                                        </a>
                                    @endif

                                    <a class="dropdown-item"
                                        href="{{ route('admin.renewal-runs.edit', ['renewal_run' => $renewalRun->id]) }}"
                                    >
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
    @include('common.datatables.scripts')

    <script>
        $(document).ready(function() {
            $('#renewal-runs-table').DataTable({
                responsive: true
            });

            initializePopovers();

            $('.add-active-individuals-to-this-run').click(function() {
                var renewalRunId = $(this).data('renewalRunId');

                bootbox.confirm({
                    size: "medium",
                    message: "Do you want to add all of the active individuals to current renewal run?",
                    callback: function(result) {
                        if (result === true) {
                            axios({
                                method: 'get',
                                url: "{{ route('admin.add_active_to_renewal_run') }}/" + renewalRunId
                            }).then(function(response) {
                                showNotice('success', 'All active individuals added to the current renewal run successfully.');

                                setTimeout(function(){
                                    window.location.reload();
                                }, 2000);
                            }).catch(function(response) {
                                showNotice('error', 'An error occurred. Have you added some/all of them to the list already?');
                            });
                        }
                    }
                });
            });
        });

        function initializePopovers() {
            {{-- Popovers for the renewal run details --}}
            $("[data-toggle='popover']").each(function(index, element) {
                $(element).popover({
                    content: $("#" + element.dataset.targetId).html()
                });
            });
        }
    </script>
@endpush
