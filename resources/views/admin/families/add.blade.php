@extends('admin.layouts.app', ['page' => 'family'])

@section('title', 'Add New Family')

@section('content')
<form method="post" class="form" action="{{ route('admin.families.store') }}">
    @csrf
    <div class="row">
        <div class="col-9 h3">
            <span class="text-muted">
                Families
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
        @include('admin.families.form_input', [
            'family' => optional()
        ])
    </div>
</form>
@endsection
