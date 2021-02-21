<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>

    <style>
        body {
            font-family: Arial;
            font-size: 14px;
        }
        .wrapper {
            margin: 10%;
        }
        .wrapper img {
            float: right;
            width: 17%;
        }
        .company-name {
            font-size: 1.6rem;
            font-weight: 800;
            display: inline-block;
            line-height: 144px;
        }
        .page-title {
            font-size: 1.9rem;
            font-weight: 800;
            display: inline-block;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        .page-title span {
            font-weight: 300;
        }
        .invoice-number {
            font-size: 1.3rem;
            font-weight: 900;
            margin-bottom: 40px;
            text-transform: uppercase;
        }
        .invoice-date {
            font-size: 1rem;
            font-weight: 900;
            margin-bottom: 0;
        }
        .side-address {
            font-size: 14px;
            line-height: 18px;
        }
        hr {
            border: 0;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            margin: 4px 0;
        }
        .address-content {
            display: inline-block;
            font-size: 15px;
        }
        .address-content {
            line-height: 20px;
            margin-top: -1px;
        }
        .address-container {
            margin-bottom: 6%;
            margin-top: 2%;
        }
        table {
            width: 100%;
        }
        table tr td:nth-child(3) {
            text-align: center;
        }
        thead td {
            padding: 5px 10px 10px 10px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        td {
            padding: 10px;
            padding: 10px;
            vertical-align: top;
        }
        .total-container {
            position: fixed;
            bottom: 10%;
            width: 79%;
        }
        .totals {
            float: right;
        }
        .total-row {
            padding: 5px;
        }
        .total-label {
            float: right;
        }
        .total-value {
            min-width: 55px;
            float: right;
            padding-left: 50px;
            text-align: right;
        }
        .float-right {
            float: right;
        }
        .float-left {
            float: left;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="row">
            <div class="company-name">
                {{ config('app.name') }} Inc
            </div>

            <img src="{{ asset('images/receipts/logo.png') }}" alt="logo">

            <div class="clearfix"></div>
        </div>

        <div class="row">
            <div class="float-left">
                <div class="page-title">
                    INVOICE
                </div>

                <div class="invoice-number">
                    NUMBER: {{ $receipt->id }}
                </div>

                <div class="invoice-date">
                    {{ $receipt->getDateCarbon()->format('l, j F Y') }}
                </div>
            </div>

            <div class="float-right side-address">
                <span>957 Jarrahdale Road,</span><br>
                <span>Jarrahdale, WA, 6124.</span><br>
                <span>ABN : 32 713 162 962</span><br>
                <br>
                <span>Email : info@jarrahdaleshooters.org.au</span><br>
            </div>

            <div style="clear: both;"></div>
        </div>

        <hr>

        <div class="row address-container">
            <div class="float-left">
                <div class="address-content">
                    {{ $receipt->individual[0]->first_name . ' ' . $receipt->individual[0]->middle_name . ' ' . $receipt->individual[0]->surname }}<br>
                    {{ $receipt->individual[0]->getFullAddress() }} <br>
                    {{ $receipt->getEntitySuburbLabel() }}
                    {{ $receipt->getEntityStateLabel() }}
                    {{ $receipt->individual[0]->post_code }}
                    <br>
                </div>
            </div>

            <div style="clear: both;"></div>
        </div>

        <div class="row item-contailer">
            <div class="col-12">
                <table>
                    <thead>
                        <tr>
                            <td width="85%">ITEM <br> DESCRIPTION</td>
                            <td width="15%">AMOUNT</td>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $totalDiscount = 0;
                            $totalAmount = 0;
                        @endphp

                        @foreach ($receipt->items as $item)
                            <tr>
                                <td>{{ $item->description }}</td>
                                <td>
                                    ${{ formattedRound($item->amount) }}
                                </td>
                            </tr>

                            @php
                                $totalDiscount += $item->amount < 0 ? abs(formattedRound($item->amount)) : 0;
                                $totalAmount += $item->amount < 0 ? 0 : formattedRound($item->amount);
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row total-container">
            <hr>
            <hr>
            <div class="totals">
                <div class="total-row">
                    <span class="total-value">
                        ${{ formattedRound($totalAmount) }}
                    </span>

                    <span class="total-label">
                        TOTAL AMOUNT :
                    </span>

                    <div style="clear: both;"></div>
                </div>

                <div class="total-row">
                    <span class="total-value">
                        $0.00
                    </span>

                    <span class="total-label">
                        GST AMOUNT :
                    </span>
                    <div style="clear: both;"></div>
                </div>

                <div class="total-row">
                    <span class="total-value">
                        ${{ formattedRound($totalDiscount) }}
                    </span>

                    <span class="total-label">
                        DISCOUNT AMOUNT :
                    </span>
                    <div style="clear: both;"></div>
                </div>

                <div class="total-row">
                    <span class="total-value">
                        ${{ $receipt->getReceivedAmount() }}
                    </span>

                    <span class="total-label">
                        <strong>
                            TOTAL AMOUNT PAID :
                        </strong>
                    </span>
                    <div style="clear: both;"></div>
                </div>
                <div style="clear: both;"></div>

                <div class="total-row">
                    <span class="total-value">
                        ${{ number_format($totalAmount - $totalDiscount - $receipt->getReceivedAmount(), 2) }}
                    </span>

                    <span class="total-label">
                        <strong>
                            TOTAL AMOUNT OWING :
                        </strong>
                    </span>
                    <div style="clear: both;"></div>
                </div>
                <div style="clear: both;"></div>
            </div>
            <div style="clear: both;"></div>

            <hr>
            <hr>
        </div>
    </div>
</body>
</html>
