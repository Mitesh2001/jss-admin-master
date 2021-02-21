<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalTitle">Manage line item</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="item-add-url" class="d-none">
                    {{ route('admin.receipts.items.store', ['receipt' => $receipt->id]) }}
                </span>

                <span id="item-edit-url" class="d-none">{{ route ('admin.receipts.items.update', [
                    'receipt' => $receipt->id,
                    'item' => 'receiptEditItemIdHere',
                ]) }}</span>

                <span id="item-edit-method" class="d-none">@method('PUT')</span>

                <form method="post" class="form" id="item-form">
                    @csrf

                    <div class="form-group">
                        <label for="receipt-item-code-id">Receipt Code:</label>

                        <select id="receipt-item-code-id" name="receipt_item_code_id" class="form-control" required>
                            <option value="">Please select receipt code</option>

                            @foreach ($receiptCodes as $receiptCode)
                                <option value="{{ $receiptCode->id }}"
                                    data-amount="{{ $receiptCode->amount }}"
                                    data-description="{{ $receiptCode->description }}"
                                >
                                    {{ $receiptCode->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="receipt-type-selection">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio"
                                class="custom-control-input"
                                id="membership"
                                name="type_id"
                                value="1"
                                checked
                            >

                            <label class="custom-control-label" for="membership">
                                Membership
                            </label>
                        </div>

                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio"
                                class="custom-control-input"
                                id="discipline"
                                name="type_id"
                                value="2"
                            >

                            <label class="custom-control-label" for="discipline">
                                Discipline
                            </label>
                        </div>
                    </div>

                    <div class="form-group" id="receipt-disciplines">
                        <label for="receipt-item-code-id">Select Disciplines:</label>

                        <select id="discipline-id" name="discipline_id" class="form-control">
                            <option value="">Please select a discipline</option>

                            @foreach ($disciplines as $discipline)
                                <option value="{{ $discipline->id }}"
                                    data-adult-price="{{ $discipline->adult_price }}"
                                    data-family-price="{{ $discipline->family_price }}"
                                    data-pensioner-price="{{ $discipline->pensioner_price }}"
                                >
                                    {{ $discipline->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>

                        <input type="text"
                            class="form-control"
                            id="description"
                            name="description"
                            required
                        >
                    </div>

                    <label for="amount">Amount:</label>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">$</span>
                        </div>

                        <input type="number"
                            pattern="\d{1,6}(\.\d{0,2})?"
                            class="form-control"
                            name="amount"
                            id="amount"
                            step="0.01"
                            required
                        >
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="save-item" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    var itemForm = document.getElementById('item-form');
    var membershipTypes = @json($membershipTypes);
    var itemAddUrl = document.getElementById('item-add-url').innerHTML;
    var itemEditUrl = document.getElementById('item-edit-url').innerHTML;
    var putMethodInputContainer = document.getElementById('item-edit-method');
    var putMethodInput = putMethodInputContainer.getElementsByTagName('input')[0];
    var saveItem = document.getElementById('save-item');

    $(document).ready(function () {
        $('#receipt-item-code-id').on('change', function (e) {
            updateFormInputsAsPerReceiptItemCode();
            updateReceiptItemAmountAndDescription();
        });

        $('input[name=type_id]').on('change', function () {
            updateFormInputsAsPerReceiptItemCode();
            updateReceiptItemAmountAndDescription();
        });

        $('#item-form #discipline-id').on('change', function () {
            updateReceiptItemAmountAndDescription();
        });

        $('#itemModal').on('show.bs.modal', function (e) {
            itemForm.reset();
            updateFormInputsAsPerReceiptItemCode();
            updateReceiptItemAmountAndDescription();

            if (e.relatedTarget.tagName == 'BUTTON'){
                itemForm.action = itemAddUrl;
                $('#item-form input[name="_method"]').remove();
                return;
            }

            updateItemFormForEdit(e);
        });

        saveItem.addEventListener('click', function(e) {
            if ($('#item-form')[0].checkValidity() == false) {
                $('<input type="submit">').hide().appendTo($('#item-form')).click().remove();

                return false;
            }

            saveItem.disabled = true;

            $.ajax({
                method: "POST",
                url: itemForm.action,
                data: $("#item-form").serialize()
            }).done(function( response ) {
                if ($("#item-form").find('input[name="_method"]').length) {
                    editItem(response.item);
                } else {
                    addReceiptItem(response.item);
                }

                saveItem.disabled = false;
                $('#itemModal').modal('hide');

                $("input#total-amount").val(response.receiptTotalAmount);
                globalTotalAmount = parseFloat(response.receiptTotalAmount);

                showNotice("success", "Item saved successfully.");
            });
        });
    });

    function updateItemFormForEdit(e) {
        var itemId = e.relatedTarget.dataset.itemId;

        itemForm.action = itemEditUrl.replace('receiptEditItemIdHere', itemId);
        itemForm.appendChild(putMethodInput);

        var itemRow = document.getElementById("item-id-" + itemId);
        var receiptItemCode = itemRow.getElementsByClassName("receipt-item")[0].dataset;
        $("#item-form select[name='receipt_item_code_id']").val(receiptItemCode.codeId);

        if (parseInt(receiptItemCode.disciplineId)) {
            $('#discipline').prop('checked', true);
            $("#item-form select[name='discipline_id']").val(receiptItemCode.disciplineId);
        }

        var description = itemRow.getElementsByClassName("description")[0].innerHTML;
        $("#item-form input[name='description']").val(description);

        var amount = itemRow.getElementsByClassName("amount")[0].innerHTML;
        $("#item-form input[name='amount']").val(amount);

        updateFormInputsAsPerReceiptItemCode();
    }

    function editItem(item) {
        var itemRow = document.getElementById("item-id-" + item.id);

        setItemValuesIn(itemRow, item);
    }

    function updateFormInputsAsPerReceiptItemCode() {
        var selectedReceiptItemCode = $("#receipt-item-code-id option:selected").val();
        if ($.inArray(parseInt(selectedReceiptItemCode), [1, 2, 3]) !== -1) {
            $('#receipt-type-selection').slideDown();
        } else {
            $('#receipt-type-selection').slideUp();
        }

        if (
            $.inArray(parseInt(selectedReceiptItemCode), [1, 2, 3]) !== -1 &&
            $('input[name=type_id]:checked').val() == 2
        ) {
            $('#receipt-disciplines').slideDown();
            return;
        }

        $('#receipt-disciplines').slideUp();
    }

    function updateReceiptItemAmountAndDescription() {
        var selectedReceiptItemCode = $("#receipt-item-code-id option:selected");
        var selectedReceiptItemType = $('input[name=type_id]:checked').val();
        var membership = getSpecifiedEntity(membershipTypes, selectedReceiptItemCode.val());

        $("#item-form input[name='amount']").val(
            selectedReceiptItemCode.data('amount') != 0 ? selectedReceiptItemCode.data('amount') : ''
        );
        $("#item-form input[name='description']").val(selectedReceiptItemCode.data('description'));

        if ($.inArray(parseInt(selectedReceiptItemCode.val()), [1, 2, 3]) !== -1) {
            if (selectedReceiptItemType == 1) {
                $("#item-form input[name='amount']").val(
                    membership.price != 0 ? membership.price : ''
                );
                $("#item-form input[name='description']").val(membership.label + ' Membership');

                return;
            }

            var selectedReceiptItemDiscipline = $("#discipline-id option:selected");
            var discipline = getSpecifiedEntity(disciplines, selectedReceiptItemDiscipline.val())

            if (discipline) {
                $("#item-form input[name='amount']").val(
                    selectedReceiptItemDiscipline.data(membership.label.toLowerCase() + '-price') != 0 ?
                    selectedReceiptItemDiscipline.data(membership.label.toLowerCase() + '-price') : ''
                );

                $("#item-form input[name='description']").val(membership.label + ' ' + discipline.label);
            }
        }
    }
</script>
@endpush
