<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle remove-down-icon" type="button" data-toggle="dropdown">
        <i class="fa fa-ellipsis-h"></i>
    </button>

    <div class="dropdown-menu">
        <a class="dropdown-item remove-individual"
            data-id="{{ $individual->idCard->id }}"
            data-individual-id="{{ $individual->id }}"
            data-is-printrun="{{ $individual->idCard->is_added_for_printrun }}"
        >
            <i class="fa fa-close"></i> Remove
        </a>
    </div>
</div>
