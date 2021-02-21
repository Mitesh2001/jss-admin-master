<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WWC Cards Report</title>
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
        .text-danger {
            color: #f86c6b;
        }
        .text-success {
            color: #4dbd74;
        }
        .text-warning {
            color: #ffc107;
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
                REPORT
            </h3>

            <div>
                <i>{{ now()->format('d/m/Y g:i:s A') }}</i>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Member Number</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>WWC Card Number</th>
                    <th>WWC Expiry</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($individuals as $individual)
                    <tr>
                        <td>{{ $individual->getMembershipNumber() }}</td>
                        <td>{{ $individual->getName() }}</td>
                        <td>{{ $individual->email_address }}</td>
                        <td>{{ $individual->wwc_card_number }}</td>
                        <td>{!! $individual->status !!}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <hr><hr>
    </div>
</body>
</html>
