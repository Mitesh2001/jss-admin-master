<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members Attendances</title>
    <style>
        body {
            text-transform: uppercase;
            font-family: Arial;
            font-size: 15px
        }
        .container {
            margin: 5% 5%;
        }
        .headings {
            margin-bottom: 10px;
            font-weight: 600;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="headings">
            Member Name: {{ auth()->guard('member')->user()->getName() }} <br><br>
            Membership #: {{ auth()->guard('member')->user()->getMembershipNumber() }}
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Discipline</th>
                    <th>Score</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance['event_date'] }}</td>
                        <td>{{ $attendance['discipline_label'] }}</td>
                        <td>{{ $attendance['score'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
