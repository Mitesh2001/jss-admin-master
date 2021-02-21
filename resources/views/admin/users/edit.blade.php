@extends('admin.layouts.app', ['page' => 'captain'])

@section('title', 'Edit Captain')

@section('content')
<form method="post"
    class="form"1
    action="{{ route('admin.users.update', ['user' => $user->id]) }}"
>
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-9 h3">
            <span class="text-muted">
                Captains
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
        @include('admin.users.form_input')
    </div>
</form>
@endsection
