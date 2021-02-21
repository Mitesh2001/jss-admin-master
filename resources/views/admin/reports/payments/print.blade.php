<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments Report</title>
    <style>
        hr {
            border: 0;
            border-top: 1px solid #e2e1e1;
            margin: 3px 0;
        }
        h3 {
            margin: 5px 0 10px;
        }
        body {
            text-transform: uppercase;
            font-family: Arial;
            font-size: 15px
        }
        .container {
            margin: 0 5%;
        }
        .table {
            width: 100%;
        }
        .table thead tr th {
            padding: 10px 10px 10px 0;
            border-top: 1px solid #e2e1e1;
            border-bottom: 1px solid #e2e1e1;
            text-align: left;
        }
        .table tbody tr:first-child td {
            border-top: 1px solid #e2e1e1;
        }
        .table tbody tr td {
            padding: 7px 7px 7px 0;
        }
        .totals {
            margin-right: 8%;
        }
        .total-label {
            font-weight: 600;
            padding: 7px;
            float: right;
        }
        .total-value {
            float: right;
            font-weight: 600;
            padding: 7px;
            width: 20%;
        }
        .float-right {
            float: right;
        }
        .total-container {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container">
        <hr><hr>
        <div>
            <strong>
                {{ config('app.name') }} <br>
            </strong>
        </div>
        <hr>
        <div>
            <h3>
                {{ $startDate }} &nbsp; TO : &nbsp; {{ $endDate }} &nbsp;&nbsp;&nbsp;
                {{ $reportType }} REPORT
            </h3>

            <div>
                <i>{{ now()->format('d/m/Y g:i:s A') }}</i>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>RCPT NUM</th>
                    <th>DATE</th>
                    <th>NAME</th>
                    <th>AMOUNT</th>
                    <th>Fee</th>
                </tr>
            </thead>
            @php
                $totalAmount = $totalFees = 0;
            @endphp

            <tbody>
                @forelse ($receiptItems as $receiptItem)
                    @php
                        $totalAmount += $receiptItem->amount - $receiptItem->getCalculatedFee();
                        $totalFees += $receiptItem->getCalculatedFee();
                    @endphp
                    <tr>
                        <td>{{ $receiptItem->receipt->id }}</td>
                        <td>{{ $receiptItem->paid_at }}</td>
                        <td>{{ $receiptItem->code->label . ' - ' . $receiptItem->getLabel() }}</td>
                        <td>${{ formattedRound($receiptItem->amount) }}</td>
                        <td>${{ $receiptItem->getCalculatedFee() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <hr><hr>

        <div class="totals">
            <div class="total-container">
                <div class="total-value">
                    : ${{ formattedRound($receiptItems->sum('amount')) }}
                </div>

                <div class="total-label">
                    TOTAL AMOUNT
                </div>
            </div>

            <div class="total-container">
                <div class="total-value">
                    : ${{ formattedRound($totalFees) }}
                </div>

                <div class="total-label">
                    TOTAL FEES
                </div>
            </div>

            <div class="total-container">
                <div class="total-value">
                    : ${{ formattedRound($totalAmount) }}
                </div>

                <div class="total-label">
                    TOTAL PAYMENTS
                </div>
            </div>
        </div>
    </div>
</body>
</html>
