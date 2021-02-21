@extends('admin.layouts.app', ['page' => 'individual'])

@section('title', 'Edit Individual')

@section('content')
<form method="post" class="form" action="{{ route('admin.individuals.update', ['individual' => $individual->id]) }}">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-9 h3">
            <span class="text-muted">
                Individuals
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
        @include('admin.individuals.includes.details')

        <div class="col-12 col-md-6 mb-5">
            <div class="card">
                <div class="card-body p-3">
                    @include('admin.individuals.includes.membership', [
                        'individualMembership' => $individualMembership ?? optional()
                    ])

                    @include('admin.individuals.includes.firearms')

                    @include('admin.individuals.includes.disciplines')

                    @include('admin.individuals.includes.range_officers')

                    @include('admin.individuals.includes.keys_issued')
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row">
    @include('admin.individuals.includes.events')

    @include('admin.individuals.includes.receipts')
</div>

@include('admin.individuals.includes.event_model', [
    'addRoute' => route(
        'admin.individuals.events.store',
        ['individual' => $individual->id]
    ),
    'editRoute' => route(
        'admin.individuals.events.update',
        ['individual' => $individual->id, 'event' => 'eventIdHere']
    ),
])

@include('admin.individuals.includes.discipline_model', [
    'addRoute' => route(
        'admin.individuals.disciplines.store',
        ['individual' => $individual->id]
    ),
    'editRoute' => route(
        'admin.individuals.disciplines.update',
        ['individual' => $individual->id, 'discipline' => 'disciplineIdHere']
    ),
])

@include('admin.individuals.includes.range_officer_modal', [
    'addRoute' => route(
        'admin.individuals.range_officers.store',
        ['individual' => $individual->id]
    ),
    'editRoute' => route(
        'admin.individuals.range_officers.update',
        ['individual' => $individual->id, 'range_officer' => 'rangeOfficerIdHere']
    ),
])
@endsection
