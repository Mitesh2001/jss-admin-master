<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle remove-down-icon" type="button" data-toggle="dropdown">
        <i class="fa fa-ellipsis-h"></i>
    </button>

    <div class="dropdown-menu">
        <a class="dropdown-item edit-key-link"
            data-id="{{ $key->id }}"
            data-individual-id="{{ $key->individual_id }}"
            data-key-type="{{ $key->key_type }}"
            data-key-number="{{ $key->key_number }}"
            data-issued-at="{{ $key->issued_at }}"
            data-deposit-amount="{{ $key->deposit_amount }}"
        >
            <i class="fa fa-edit"></i> Edit
        </a>

        @if (! $key->loosed_at)
            <a class="dropdown-item mark-as-lost"
                data-url="{{ route('admin.keys.mark_as_lost', [ 'key' => $key->id ]) }}"
            >
                <i class="fa fa-key"></i>
                Mark as lost
            </a>
        @endif

        @if (! $key->returned_at)
            <a class="dropdown-item mark-as-returned"
                data-url="{{ route('admin.keys.mark_as_returned', [ 'key' => $key->id ]) }}"
            >
                <i class="fa fa-reply"></i>
                Mark as returned
            </a>
        @endif

        <form method="post"
            action="{{ route('admin.keys.destroy', ['key' => $key->id]) }}"
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
