<div class="col-12 mb-3">
    <div class="card">
        <div class="card-body p-3">
            <h5>Line Items</h5>

            <div id="items-container">
                <div class="row font-weight-bold">
                    <div class="col-3">Receipt Code</div>

                    <div class="col-3">Description</div>

                    <div class="col-2">Amount</div>

                    <div class="col-2">Total</div>
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-outline-dark float-right mt-3" data-toggle="modal" data-target="#itemModal">
                Add Line Item
            </button>

            <div class="clearfix"></div>
        </div>
    </div>
</div>

<template id="item-template">
    <div class="row">
        <div class="col-3 receipt-item"></div>

        <div class="col-3 description"></div>

        <div class="col-2">
            $<span class="amount"></span>
        </div>

        <div class="col-2">
            $<span class="total-amount"></span>
        </div>

        <div class="col-1 text-center">
            <div class="dropdown">
                <button class="btn btn-secondary px-1 py-0 mb-1 dropdown-toggle remove-down-icon" type="button" data-toggle="dropdown">
                    <i class="fa fa-ellipsis-h"></i>
                </button>

                <div class="dropdown-menu">
                    <a class="dropdown-item i-edit pointer"
                        data-toggle="modal"
                        data-target="#itemModal"
                    >
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <a class="dropdown-item i-trash pointer">
                        <i class="fa fa-trash"></i> Delete
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
    var items = @json($receipt->items);
    var disciplines = @json($disciplines);
    var itemsContainer = document.getElementById('items-container');
    var itemTemplate = document.getElementById('item-template');
    var itemDeleteUrl = "{{ route('admin.receipts.items.destroy', ['receipt' => $receipt->id, 'item' => 'receiptItemIdHere']) }}";

    $( document ).ready(function() {
        if (items && items.length) {
            for (var i = 0; i < items.length; i++) {
                addReceiptItem(items[i]);
            }
        }

        $(itemsContainer).on( "click", "a.i-trash", function(e) {
            var deleteDisciplineUrl = itemDeleteUrl.replace('receiptItemIdHere', this.dataset.itemId);

            bootbox.confirm({
                size: "medium",
                message: "Are you sure?",
                callback: function(result) {
                    if (result === true) {
                        $.ajax({
                            method: "DELETE",
                            url: deleteDisciplineUrl
                        }).done(function( receiptTotalAmount ) {
                            $("div#item-id-" + itemId).remove();
                            $("input#total-amount").val(receiptTotalAmount);
                            globalTotalAmount = parseFloat(receiptTotalAmount);

                            showNotice("success", "Item deleted successfully.");
                        });
                    }
                }
            });
        });
    });

    function addReceiptItem(item) {
        var iElement = itemTemplate.content.querySelector('div.row').cloneNode(true);
        iElement.setAttribute("id", "item-id-" + item.id);

        iElement = setItemValuesIn(iElement, item);
        itemsContainer.appendChild(iElement);

        var addPayment = document.getElementById('add-payment');
        addPayment.classList.remove('d-none');
    }

    function setItemValuesIn(element, item) {
        element.getElementsByClassName("receipt-item")[0].dataset.codeId = item.code.id;
        element.getElementsByClassName("receipt-item")[0].dataset.disciplineId = item.discipline_id;
        element.getElementsByClassName("receipt-item")[0].innerHTML = getCodeLabel(item);

        element.getElementsByClassName("description")[0].innerHTML = item.description;
        element.getElementsByClassName("amount")[0].innerHTML = parseFloat(item.amount).toFixed(2);
        element.getElementsByClassName("total-amount")[0].innerHTML = parseFloat(item.amount);

        element.getElementsByClassName("i-edit")[0].dataset.itemId = item.id;
        element.getElementsByClassName("i-trash")[0].dataset.itemId = item.id;

        return element;
    }

    function getCodeLabel(item) {
        var itemLabelCode = item.code.label;
        var itemLabel = '';

        if ($.inArray(item.code.id, [1, 2, 3]) != -1) {
            itemLabel = item.discipline_id ? ' ' + getSpecifiedEntity(disciplines, item.discipline_id).label : ' Membership';
        }

        return itemLabelCode + itemLabel
    }

    function getSpecifiedEntity(entities, entity) {
        return $.grep(entities, function(item) {
            return item.id == entity;
        })[0];
    }
</script>
@endpush
