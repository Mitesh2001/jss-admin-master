@extends('admin.layouts.app', ['page' => 'captain'])

@section('title', 'Add New Captain')

@section('content')
<form method="post" class="form" action="{{ route('admin.users.store') }}">
    @csrf
    <div class="row">
        <div class="col-9 h3">
            <span class="text-muted">
                Captains
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
        @include('admin.users.form_input', [
            'user' => optional()
        ])
    </div>
</form>
@endsection
