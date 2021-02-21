<div class="row mb-3" id="filters-container">
    <div class="col-6 h4" style="color: #5a5b5d;">
        Filters
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body p-3">
                <form method="post" action="{{ route('admin.range_officers.filter') }}">
                    @csrf

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="discipline">Discipline:</label>

                                <select name="discipline" id="discipline" class="form-control">
                                    <option value="all" selected>All Disciplines</option>

                                    @foreach ($disciplines as $discipline)
                                        <option value="{{ $discipline->id }}"
                                            {{ session('range_officer_discipline') == $discipline->id ? 'selected' : '' }}
                                        >
                                            {{ $discipline->label }}
                                        </option>
                                    @endforeach
                                </select>
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
