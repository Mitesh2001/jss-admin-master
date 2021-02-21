@extends('admin.layouts.app', ['page' => 'family'])

@section('title', 'Edit Family')

@section('content')
<form method="post"
    class="form"1
    action="{{ route('admin.families.update', ['family' => $family->id]) }}"
>
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-9 h3">
            <span class="text-muted">
                Families
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
        @include('admin.families.form_input')
    </div>
</form>
@endsection
