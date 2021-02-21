@extends('admin.layouts.app', ['page' => 'renewal_runs'])

@section('title', 'Add New Renewal Run')

@section('content')
<form method="post" class="form" action="{{ route('admin.renewal-runs.store') }}">
    @csrf

    <div class="row">
        <div class="col-9 h3">
            <span class="text-muted">
                Renewal Runs
            </span>
            | Add new
        </div>

        <div class="col-3 text-right header-form-controls">
            <div class="form-group d-inline-block">
                <button class="btn btn-success btn-sm">
                    Save
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <h5>Renewal Run Details</h5>

                    @include('admin.renewal_runs.form', [
                        'renewalRun' => optional(),
                        'currentYear' => now()->year
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
