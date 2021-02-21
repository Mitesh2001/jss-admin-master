<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle remove-down-icon" type="button" data-toggle="dropdown">
        <i class="fa fa-ellipsis-h"></i>
    </button>

    <div class="dropdown-menu">
        <a class="dropdown-item edit-firearm-link"
            data-id="{{ $firearm->id }}"
            data-individual-ids="{{ json_encode($firearm->individuals->pluck('id')->toArray(), true) }}"
            data-firearm-type="{{ $firearm->firearm_type_id }}"
            data-serial="{{ $firearm->serial }}"
            data-make="{{ $firearm->make }}"
            data-model="{{ $firearm->model }}"
            data-calibre="{{ $firearm->calibre }}"
            data-discipline-id="{{ $firearm->discipline_id }}"
            data-support-granted-at="{{ $firearm->support_granted_at }}"
        >
            <i class="fa fa-edit"></i> Edit
        </a>

        @if (! $firearm->support_removed_at)
            <a class="dropdown-item remove-Support"
                data-id="{{ $firearm->id }}"
            >
                <i class="fa fa-times"></i>
                Remove Support
            </a>
        @endif

        @if (! $firearm->mark_as_disposed_at)
            <a class="dropdown-item mark-as-disposed"
                data-id="{{ $firearm->id }}"
            >
                <i class="fa fa-times-circle"></i>
                Mark as Disposed
            </a>
        @endif
    </div>
</div>
