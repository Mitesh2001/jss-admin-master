<a
    href="{{ route('admin.individuals.edit', ['individual' => $individual->id]) }}"
    target="_blank"
>
    {{ $individual->first_name . ' ' . $individual->surname }}
</a>
