@extends('admin.layouts.app', ['page' => 'renewal_runs'])

@section('title', 'Edit Renewal Run')

@section('content')
<form method="post" class="form" action="{{ route('admin.renewal-runs.update', ['renewal_run' => $renewalRun->id]) }}">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-9 h3">
            <span class="text-muted">
                Renewal Runs
            </span>
            | Edit
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

                    @include('admin.renewal_runs.form')
                </div>
            </div>
        </div>
    </div>
@endsection
