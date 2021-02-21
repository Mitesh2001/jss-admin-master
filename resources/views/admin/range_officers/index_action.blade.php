<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle remove-down-icon" type="button" data-toggle="dropdown">
        <i class="fa fa-ellipsis-h"></i>
    </button>

    <div class="dropdown-menu">
        <a class="dropdown-item edit-range-officer-link"
            data-id="{{ $officer->id }}"
            data-individual-id="{{ $officer->individual_id }}"
            data-discipline-id="{{ $officer->discipline_id }}"
            data-added-date="{{ $officer->added_date }}"
        >
            <i class="fa fa-edit"></i> Edit
        </a>

        <form method="post"
            action="{{ route('admin.range_officers.destroy', ['range_officer' => $officer->id]) }}"
            class="inline pointer"
        >
            @csrf
            @method('DELETE')

            <a class="dropdown-item delete-button" data-message="Are you sure you want to delete this record?">
                <i class="fa fa-trash"></i> Delete
            </a>
        </form>
    </div>
</div>
