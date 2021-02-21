@extends('admin.layouts.app', ['page' => 'discipline'])

@section('title', 'Edit Discipline')

@section('content')
<form method="post"
    class="form"1
    action="{{ route('admin.disciplines.update', ['discipline' => $discipline->id]) }}"
>
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-9 h3">
            <span class="text-muted">
                Disciplines
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
        @include('admin.disciplines.form_input')
    </div>
</form>
@endsection
