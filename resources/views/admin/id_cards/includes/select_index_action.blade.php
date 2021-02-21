<div class="custom-control custom-checkbox">
    <input type="checkbox"
        class="custom-control-input select-checkbox"
        id="selection-checkbox-{{ $individual->idCard->id }}"
        name="id_cards[]"
        value="{{ $individual->idCard->id }}"
    >

    <label class="custom-control-label" for="selection-checkbox-{{ $individual->idCard->id }}"></label>
</div>
