<div class="mb-5">
    <a class="btn btn-sm btn-primary float-right"
        href="{{ route('admin.firearms.index', [
            'individual_id' => $individual->id,
            'individual_name' => $individual->getName(),
        ]) }}"
    >
        Firearm Support
    </a>
</div>
