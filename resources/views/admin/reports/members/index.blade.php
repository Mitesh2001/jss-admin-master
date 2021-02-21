@extends('admin.layouts.app', ['page' => 'members_report'])

@section('title', 'Members Report')

@push('styles')
    @include('common.datatables.styles')

    <style>
        .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
            width: 500px !important;
        }
    </style>
@endpush

@section('content')
    @include ('admin.reports.members.filters')

    <div class="row">
        <div class="col-6 h3" style="color: #5a5b5d;">
            Members
        </div>

        <div class="col-6 text-right header-form-controls">
            <a href="{{ route('admin.reports.members.print',
                [
                    'discipline' => session('members_discipline_type') != 0 ? session('members_discipline_type') : '0',
                    'expiration_status' => session('members_expiration_status'),
                    'membership_status' => session('membership_status'),
                    'member_types' => session('member_types'),
                    'lifetime_status' => session('lifetime_status'),
                    'is_next_year' => session('is_next_year')
                ]) }}"
                class="btn btn-dark"
                target="_blank"
            >
                <i class="fa fa-file-text"></i>
                &nbsp; PDF Report
            </a>

            <a href="{{ route('admin.reports.members.csv',
                [
                    'discipline' => session('members_discipline_type') != 0 ? session('members_discipline_type') : '0',
                    'expiration_status' => session('members_expiration_status'),
                    'membership_status' => session('membership_status'),
                    'member_types' => session('member_types'),
                    'lifetime_status' => session('lifetime_status'),
                    'is_next_year' => session('is_next_year')
                ]) }}"
                class="btn btn-secondary"
                target="_blank"
            >
                <i class="fa fa-file-text"></i>
                &nbsp; CSV Report
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive m-2">
            <table class="table table-striped table-hover clickable-table" id="members-table">
                <thead>
                    <tr>
                        <th>Member number</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Member Type</th>
                        <th>Expiry</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    @include('common.datatables.scripts')

    <script>
        $( document ).ready(function() {
            $('#members-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.reports.members.datatables') }}",
                columns: [
                    { name: 'membership.membership_number', orderable: false, searchable: false },
                    { name: 'name' },
                    { name: 'mobile_phone_number', orderable: false },
                    { name: 'email_address' },
                    { name: 'membership.status', orderable: false, searchable: false },
                    { name: 'member_type', orderable: false, searchable: false },
                    { name: 'membership.expiry', orderable: false, searchable: false },
                ],
                order: [[ 1, "desc" ]]
            });
        });
    </script>
@endpush
