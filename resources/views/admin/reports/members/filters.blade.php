<div class="row mb-3">
    <div class="col-6 h4" style="color: #5a5b5d;">
        Filters
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body p-3">
                <form method="post" action="{{ route('admin.reports.members.filter') }}">
                    @csrf

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="discipline">Section/Discipline:</label>

                                <select id="discipline" class="form-control" name="discipline">
                                    <option value="0" {{ session('members_discipline_type') == 0 ? 'selected' : '' }}>
                                        All
                                    </option>

                                    @foreach($disciplines as $discipline)
                                        <option value="{{ $discipline->id }}"
                                            {{ session('members_discipline_type') == $discipline->id ? 'selected' : '' }}
                                        >
                                            {{ $discipline->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="members-status">Membership Status:</label>

                                <select id="members-status" class="form-control" name="membership_status">
                                    <option value="0" {{ session('membership_status') == 0 ? 'selected' : '' }}>
                                        All
                                    </option>

                                    <option value="1"
                                        {{ session('membership_status') == 1 ? 'selected' : '' }}
                                    >
                                        Active
                                    </option>

                                    <option value="2" {{ session('membership_status') == 2 ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="members-membership-status">Expiration Status:</label>

                                <select id="members-membership-status" class="form-control" name="members_expiration_status">
                                    <option value="0" {{ session('members_expiration_status') == 0 ? 'selected' : '' }}>
                                        All
                                    </option>

                                    <option value="1" {{ session('members_expiration_status') == 1 ? 'selected' : '' }}>
                                        Expired
                                    </option>

                                    <option value="2" {{ session('members_expiration_status') == 2 ? 'selected' : '' }}>
                                        Non-expired
                                    </option>

                                    <option value="3" {{ session('members_expiration_status') == 3 ? 'selected' : '' }}>
                                        Expiring this year
                                    </option>

                                    <option value="4" {{ session('members_expiration_status') == 4 ? 'selected' : '' }}>
                                        Expired Last year
                                    </option>

                                    <option value="5" {{ session('members_expiration_status') == 5 ? 'selected' : '' }}>
                                        Expiring next year
                                    </option>

                                    <option value="6" {{ session('members_expiration_status') == 6 ? 'selected' : '' }}>
                                        Expiring next year or later
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="member-types">Member Types:</label>

                                <select id="member-types" class="selectpicker" name="member_types[]" multiple>
                                    <option value="1"
                                        {{ session('member_types') && in_array(1, session('member_types')) ? 'selected' : '' }}
                                    >
                                        Adults
                                    </option>

                                    <option value="2"
                                        {{ session('member_types') && in_array(2, session('member_types')) ? 'selected' : '' }}
                                    >
                                        Juniors
                                    </option>

                                    <option value="3"
                                        {{ session('member_types') && in_array(3, session('member_types')) ? 'selected' : '' }}
                                    >
                                        Pensioners
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="lifetime-status">Lifetime Status:</label>

                                <select id="lifetime-status" class="selectpicker" name="lifetime_status[]" multiple>
                                    <option value="lifetime_club"
                                        {{ session('lifetime_status') && in_array('lifetime_club', session('lifetime_status')) ? 'selected' : '' }}
                                    >
                                        Lifetime Club
                                    </option>

                                    @foreach ($disciplines as $discipline)
                                        <option value="{{ $discipline->id }}"
                                            {{ session('lifetime_status') && in_array($discipline->id, session('lifetime_status')) ? 'selected' : '' }}
                                        >
                                            Lifetime {{ $discipline->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="is_next_year" value="0">

                                    <input type="checkbox"
                                        class="custom-control-input"
                                        value="1"
                                        id="next-year-calculation"
                                        name="is_next_year"
                                        {{ session('is_next_year') == 1 ? 'checked' : '' }}
                                    >

                                    <label class="custom-control-label" for="next-year-calculation">
                                        Base Adult/Junior age calculations upon next year's membership
                                    </label>
                                </div>
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
