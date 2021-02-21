<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle remove-down-icon" type="button" data-toggle="dropdown">
        <i class="fa fa-ellipsis-h"></i>
    </button>

    <div class="dropdown-menu">
        <a class="dropdown-item"
            href="{{ route('admin.disciplines.edit', ['discipline' => $discipline->id]) }}"
        >
            <i class="fa fa-edit"></i>
            Edit
        </a>

        <form method="post"
            action="{{ route('admin.disciplines.destroy', ['discipline' => $discipline->id]) }}"
            class="inline pointer"
        >
            @csrf
            @method('DELETE')

            <a class="dropdown-item delete-button">
                <i class="fa fa-trash"></i> Delete
            </a>
        </form>
    </div>
</div>
