@extends('admin.layouts.app', ['page' => 'individual'])

@section('title', 'Add New Individual')

@section('content')
<form method="post" class="form" id="add-individual-form" action="{{ route('admin.individuals.store') }}">
    @csrf
    <div class="row">
        <div class="col-9 h3">
            <span class="text-muted">
                Individuals
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
        @include('admin.individuals.includes.details', ['individual' => optional()])

        <div class="col-12 col-md-6 mb-5">
            <div class="card">
                <div class="card-body p-3">
                    <h5>Membership Details</h5>

                    <div class="alert alert-primary" role="alert">
                        You can add membership details after adding the individual.
                    </div>

                    <h5>Disciplines</h5>

                    <div class="alert alert-primary" role="alert">
                        You can add disciplines after adding the individual.
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-12 col-md-6 mb-5">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-6">
                        <h5>Events</h5>
                    </div>
                </div>

                <div class="alert alert-primary" role="alert">
                    You can add events while editing the individual.
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 mb-5">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-6">
                        <h5>Receipts</h5>
                    </div>
                </div>

                <div class="alert alert-primary" role="alert">
                    You can add receipts while editing the individual.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
