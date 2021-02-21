<div class="row mb-3" id="filters-container">
    <div class="col-6 h4" style="color: #5a5b5d;">
        Filters
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body p-3">
                <form method="post" action="{{ route('admin.firearms.filter') }}">
                    @csrf

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="discipline">Select Supporting Discipline:</label>

                                <select id="discipline" class="form-control" name="discipline">
                                    <option value="0" {{ session('firearm_discipline_type') == 0 ? 'selected' : '' }}>
                                        All
                                    </option>

                                    @foreach($disciplines as $discipline)
                                        <option value="{{ $discipline->id }}"
                                            {{ session('firearm_discipline_type') == $discipline->id ? 'selected' : '' }}
                                        >
                                            {{ $discipline->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="firearm-status">Select Firearm Status:</label>

                                <select id="firearm-status" class="form-control" name="firearm_status">
                                    <option value="0" {{ session('firearm_status') == 0 ? 'selected' : '' }}>
                                        All
                                    </option>

                                    <option value="1" {{ session('firearm_status') == 1 ? 'selected' : '' }}>
                                        Supported
                                    </option>

                                    <option value="2" {{ session('firearm_status') == 2 ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="firearm-membership-status">Select Membership Status:</label>

                                <select id="firearm-membership-status" class="form-control" name="firearm_membership_status">
                                    <option value="0" {{ session('firearm_membership_status') == 0 ? 'selected' : '' }}>
                                        All Individual
                                    </option>

                                    <option value="1"
                                        {{ session('firearm_membership_status') == 1 ? 'selected' : '' }}
                                    >
                                        Active
                                    </option>

                                    <option value="2" {{ session('firearm_membership_status') == 2 ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="membership-number">Membership #:</label>

                                <input type="text"
                                    id="membership-number"
                                    class="form-control"
                                    name="firearm_membership_number"
                                    value="{{ session('firearm_membership_number') }}"
                                >
                            </div>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-success float-right">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
