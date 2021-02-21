<div class="row mb-3">
    <div class="col-6 h4" style="color: #5a5b5d;">
        Filters
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body p-3">
                <form method="post" action="{{ route('admin.reports.payments.filter') }}">
                    @csrf

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="filter">Select Section/Discipline:</label>

                                <select id="filter" class="form-control" name="type">
                                    @foreach($disciplines as $discipline)
                                        <option value="{{ $discipline->id }}"
                                            {{ session('payment_discipline_type') == $discipline->id ? 'selected' : '' }}
                                        >
                                            {{ $discipline->label }}
                                        </option>
                                    @endforeach

                                    <option value="all" {{ session('payment_discipline_type') == 'all' ? 'selected' : '' }}>
                                        Club Memberships / Other
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="start-date">Start Date:</label>
                                    <input type="date"
                                        id="start-date"
                                        class="form-control"
                                        name="start_date"
                                        value="{{ $startDate }}"
                                    >

                                    <div class="input-group-prepend">
                                        <a class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="end-date">End Date:</label>
                                    <input type="date"
                                        id="end-date"
                                        class="form-control"
                                        name="end_date"
                                        value="{{ $endDate }}"
                                    >

                                    <div class="input-group-prepend">
                                        <a class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </a>
                                    </div>
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
