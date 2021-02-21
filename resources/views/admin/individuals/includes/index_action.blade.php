<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle remove-down-icon" type="button" data-toggle="dropdown">
        <i class="fa fa-ellipsis-h"></i>
    </button>

    <div class="dropdown-menu">
        <a class="dropdown-item"
            href="{{ route('admin.individuals.edit', ['individual' => $individual->id]) }}"
        >
            <i class="fa fa-edit"></i> Edit
        </a>

        <a class="dropdown-item change-id-card-status"
            data-individual-id="{{ $individual->id }}"
            data-status="{{ optional($individual)->idCard ? 1 : 0 }}"
            data-is-printrun="{{ optional(optional($individual)->idCard)->is_added_for_printrun }}"
        >
            <i class="fa fa-edit"></i>
            {{ optional($individual)->idCard ? 'Unmark' : 'Mark' }}
            for Card Print
        </a>

        @if ($isEligibleForRenewal)
            <a class="dropdown-item add-to-renewal-run" data-individual-id="{{ $individual->id }}">
                <i class="fa fa-refresh"></i> Add to current Renewal Run
            </a>
        @endif
    </div>
</div>
