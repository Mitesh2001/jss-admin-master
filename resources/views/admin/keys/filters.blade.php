<div class="row mb-3" id="filters-container">
    <div class="col-6 h4" style="color: #5a5b5d;">
        Filters
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body p-3">
                <form method="post" action="{{ route('admin.keys.filter') }}">
                    @csrf

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="key-status">Select Key Status:</label>

                                <select id="key-status" class="form-control" name="key_status">
                                    <option value="0" {{ session('key_status') == 0 ? 'selected' : '' }}>
                                        Active & Non Active
                                    </option>

                                    <option value="1" {{ session('key_status') == 1 ? 'selected' : '' }}>
                                        Active Keys
                                    </option>

                                    <option value="2" {{ session('key_status') == 2 ? 'selected' : '' }}>
                                        Non Active Keys
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="filter-key-type">Select Key Type:</label>

                                <select id="filter-key-type" class="form-control" name="key_type">
                                    <option value="0" {{ session('key_type') == 0 ? 'selected' : '' }}>
                                        General & Committee
                                    </option>

                                    <option value="1" {{ session('key_type') == 1 ? 'selected' : '' }}>
                                        General Keys
                                    </option>

                                    <option value="2" {{ session('key_type') == 2 ? 'selected' : '' }}>
                                        Committee Keys
                                    </option>
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
