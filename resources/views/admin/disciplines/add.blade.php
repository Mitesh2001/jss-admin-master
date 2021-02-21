@extends('admin.layouts.app', ['page' => 'discipline'])

@section('title', 'Add New Discipline')

@section('content')
<form method="post" class="form" action="{{ route('admin.disciplines.store') }}">
    @csrf
    <div class="row">
        <div class="col-9 h3">
            <span class="text-muted">
                Disciplines
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
        @include('admin.disciplines.form_input', [
            'discipline' => optional()
        ])
    </div>
</form>
@endsection
