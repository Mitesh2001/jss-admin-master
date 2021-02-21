<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle remove-down-icon" type="button" data-toggle="dropdown">
        <i class="fa fa-ellipsis-h"></i>
    </button>

    <div class="dropdown-menu">
        <a class="dropdown-item"
            href="{{ route('admin.receipts.edit', ['receipt' => $receipt->id]) }}"
        >
            <i class="fa fa-edit"></i> Edit
        </a>

        <form method="post"
            action="{{ route('admin.receipts.destroy', ['receipt' => $receipt->id]) }}"
            class="inline pointer"
        >
            @csrf
            @method('DELETE')

            <a class="dropdown-item delete-button"
                data-message="Related line items and payment details will also be deleted. Are you sure?"
            >
                <i class="fa fa-trash"></i> Delete
            </a>
        </form>

        <a class="dropdown-item"
            href="{{ route('admin.receipts.print', ['receipt' => $receipt->id]) }}"
            target="_blank"
        >
            <i class="fa fa-print"></i> Print
        </a>
    </div>
</div>
