<a
    href="{{ route('admin.individuals.edit', ['individual' => $receiptItem->receipt->individual[0]->id]) }}"
    target="_blank"
>
    {{ $receiptItem->receipt->individual[0]->getName() }}
</a>
