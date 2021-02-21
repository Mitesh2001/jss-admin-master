@extends('captain.layouts.app', ['page' => 'dashboard'])

@section('title', 'Dashboard')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-6 pt-2 h5">
                <i class="fa fa-tint"></i>
                Dashboard
            </div>
        </div>
    </div>

    <div class="card-body p-3">
        <p>
            Captain Dashboard
        </p>
    </div>
</div>
@endsection
