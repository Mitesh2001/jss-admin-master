@push('styles')
    <style>
        .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
            width: 500px !important;
        }
    </style>
@endpush

<div class="col-12 mb-5">
    <div class="card">
        <div class="card-body p-3">
            <h5>Captain Details</h5>

            <div class="row">
                <div class="col-12 col-md-6 py-2">
                    <label for="individual">Individual:</label>

                    <div class="input-group">
                        <select class="selectpicker"
                            data-live-search="true"
                            id="individual"
                            name="individual_id"
                            required
                        >
                            @foreach($individuals as $individual)
                                <option
                                    value="{{ $individual->id }}"
                                    {{ old('individual_id', $user->individual_id) == $individual->id ? 'selected' : ''  }}
                                >
                                    {{ $individual->getFullName() }} -
                                    ({{ $individual->membership->membership_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6 py-2">
                    <label for="discipline">Disciplines:</label>

                    <div class="input-group">
                        @php
                            $disciplineIds = $user->disciplines ? $user->disciplines->pluck('id')->toArray() : [];
                        @endphp

                        <select class="selectpicker"
                            data-live-search="true"
                            id="discipline"
                            name="discipline_ids[]"
                            multiple
                            required
                        >
                            @foreach($disciplines as $discipline)
                                <option
                                    value="{{ $discipline->id }}"
                                    {{ in_array($discipline->id, old('discipline_ids', $disciplineIds)) ? 'selected' : ''  }}
                                >
                                    {{ $discipline->label }}
                                </option>
                                @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="username">Username:</label>

                        <input type="text"
                            name="username"
                            class="form-control"
                            value="{{ old('username', $user->username) }}"
                            required
                        >
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="password">Password:</label>

                        <input type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            required
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
