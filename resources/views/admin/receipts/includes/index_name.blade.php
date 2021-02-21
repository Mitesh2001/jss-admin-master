<a href="{{ route('admin.individuals.edit', ['individual' => $receipt->individual[0]->id]) }}" target="_blank">
    {{ $receipt->individual[0]->first_name . ' ' . $receipt->individual[0]->surname }} ( {{ $receipt->individual[0]->getMembershipNumber() }} )
</a>
