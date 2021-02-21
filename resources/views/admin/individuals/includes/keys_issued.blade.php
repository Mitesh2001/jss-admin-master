<h5>Keys Issued</h5>

<div>
    <div class="mb-2">
        <div class="row">
            <div class="col-3">
                <div class="font-weight-bold">
                    Key Type
                </div>
            </div>

            <div class="col-3">
                <div class="font-weight-bold">
                    Key Number
                </div>
            </div>

            <div class="col-3">
                <div class="font-weight-bold">
                    Date Issued
                </div>
            </div>

            <div class="col-3">
                <div class="font-weight-bold">
                    Deposit
                </div>
            </div>
        </div>

        @forelse ($individual->issuedKeys as $key)
            <div class="row mt-2">
                <div class="col-3">
                    {{ $key->key_type == 1 ? 'General' : 'Committee' }}
                </div>

                <div class="col-3">
                    {{ $key->key_number }}
                </div>

                <div class="col-3">
                    {{ $key->issued_at }}
                </div>

                <div class="col-3">
                    {{ $key->deposit_amount }}
                </div>
            </div>
        @empty
            <div class="row">
                <div class="col-12 mt-2 h5 text-center">
                    No active keys found.
                </div>
            </div>
        @endforelse
    </div>
</div>
