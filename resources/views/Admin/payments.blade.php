<link rel="icon" href="/img/logo.jpg" type="image/x-icon">

<body id="body-pd" style="background: #80808045;">
    @extends('admin.layouts.nav')
    @section('content')
        <div class="container" style="margin-top:98px; background: aliceblue;">
            <div class="table-wrapper">
                <div class="table-title" style="border-radius: 14px;">
                    <div class="row">
                        <div class="col-sm-6">
                            <h2>Payment <b>Details</b></h2>
                        </div>
                        <div class="col-sm-6 d-flex justify-content-end align-items-center">
                            <a href="{{ route('admin.payments') }}" class="btn btn-primary mr-2">
                                <i class="material-icons">&#xE863;</i>
                                <span>Refresh</span>
                            </a>
                            <form method="GET" class="m-0 p-0">
                                <select name="status" class="form-control" style="width:200px;"
                                    onchange="this.form.submit()">
                                    <option value="">All</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Paid
                                    </option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed
                                    </option>
                                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>
                                        Refunded</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                @php
                    $sort = request('sort');
                    $order = request('order') === 'asc' ? 'desc' : 'asc';
                @endphp

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width:10%;">
                                    <a href="?sort=paymentId&order={{ $sort == 'paymentId' ? $order : 'asc' }}">
                                        Payment ID
                                        @if ($sort === 'paymentId')
                                            {{ request('order') === 'asc' ? '↑' : '↓' }}
                                        @endif
                                    </a>
                                </th>

                                <th style="width:8%;">User ID</th>

                                <th style="width:10%;">
                                    <a href="?sort=orderid&order={{ $sort == 'orderid' ? $order : 'asc' }}">
                                        Order ID
                                        @if ($sort === 'orderid')
                                            {{ request('order') === 'asc' ? '↑' : '↓' }}
                                        @endif
                                    </a>
                                </th>

                                <th style="width:12%;">Payment Method</th>

                                <th style="width:14%;">Transaction ID</th>

                                <th style="width:10%;">
                                    <a href="?sort=amount&order={{ $sort == 'amount' ? $order : 'asc' }}">
                                        Amount
                                        @if ($sort === 'amount')
                                            {{ request('order') === 'asc' ? '↑' : '↓' }}
                                        @endif
                                    </a>
                                </th>

                                <th style="width:12%;">Status</th>

                                <th style="width:18%;">
                                    <a href="?sort=created_at&order={{ $sort == 'created_at' ? $order : 'asc' }}">
                                        Date
                                        @if ($sort === 'created_at')
                                            {{ request('order') === 'asc' ? '↑' : '↓' }}
                                        @endif
                                    </a>
                                </th>
                            </tr>
                        </thead>



                        <tbody>
                            @forelse ($payments as $payment)
                                @php
                                    $rowColor = match ($payment->status) {
                                        'completed' => '#d4edda', // light green
                                        'pending' => '#e2e3e5', // light grey (badge-secondary)
                                        'failed' => '#f8d7da', // light red
                                        'refunded' => '#fff3cd', // light yellow/orange
                                        default => '#d6d8d9', // dark grey for unknown
                                    };

                                @endphp

                                <tr style="background: {{ $rowColor }}; font-size:14px;">
                                    <td>{{ $payment->paymentId }}</td>
                                    <td>{{ $payment->userid }}</td>
                                    <td>{{ $payment->orderid }}</td>
                                    <td>
                                        @if ($payment->payment_method == 'cash')
                                            Cash
                                        @elseif ($payment->payment_method == 'card')
                                            Card
                                        @elseif ($payment->payment_method == 'upi')
                                            UPI
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td data-toggle="tooltip" title="{{ $payment->transaction_id }}">
                                        {{ substr($payment->transaction_id, 0, 12) }}...
                                    </td>
                                    <td>Rs.{{ $payment->amount }}/-</td>
                                    <td>
                                        @if ($payment->status == 'completed')
                                            <span class="badge badge-success" style="font-size:14px;">Paid</span>
                                        @elseif ($payment->status == 'pending')
                                            <span class="badge badge-secondary" style="font-size:14px;">Pending</span>
                                        @elseif ($payment->status == 'failed')
                                            <span class="badge badge-danger" style="font-size:14px;">Failed</span>
                                        @elseif ($payment->status == 'refunded')
                                            <span class="badge badge-warning" style="font-size:14px;">Refunded</span>
                                        @else
                                            <span class="badge badge-dark" style="font-size:14px;">Unknown</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->created_at }}</td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="10">
                                        <div class="alert alert-info my-3">
                                            <center>
                                                <font style="font-size:22px;">No Payments Found</font>
                                            </center>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $payments->links('pagination::bootstrap-4') }}
                    </div>

                </div>
            </div>
        </div>
    @endsection

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        .table-responsive::-webkit-scrollbar {
            display: none;
        }

        .tooltip.show {
            top: -62px !important;
        }

        .table-wrapper .btn {
            float: right;
            color: #333;
            background-color: #fff;
            border-radius: 3px;
            border: none;
            outline: none !important;
            margin-left: 10px;
        }

        .table-wrapper .btn:hover {
            color: #333;
            background: #f2f2f2;
        }

        .table-wrapper .btn.btn-primary {
            color: #fff;
            background: #03A9F4;
        }

        .table-wrapper .btn.btn-primary:hover {
            background: #03a3e7;
        }

        .table-title .btn {
            font-size: 13px;
            border: none;
        }

        .table-title .btn i {
            float: left;
            font-size: 21px;
            margin-right: 5px;
        }

        .table-title .btn span {
            float: left;
            margin-top: 2px;
        }

        .table-title {
            color: #fff;
            background: #4b5366;
            padding: 16px 25px;
            margin: -20px -25px 10px;
            border-radius: 3px 3px 0 0;
        }

        .table-title h2 {
            margin: 5px 0 0;
            font-size: 24px;
        }

        table.table {
            table-layout: fixed;
        }

        table.table tr th,
        table.table tr td {
            border-color: #e9e9e9;
            padding: 14px 16px;
            vertical-align: middle;
        }

        table.table tr th:first-child {
            width: 60px;
        }

        table.table tr th:last-child {
            width: 80px;
        }

        table.table-striped tbody tr:nth-of-type(odd) {
            /* background-color: #fcfcfc; */
        }

        table.table-striped.table-hover tbody tr:hover {
            /* background: #f5f5f5; */
        }

        table.table th i {
            font-size: 13px;
            margin: 0 5px;
            cursor: pointer;
        }

        table.table th a {
            color: #ffffff;
            text-decoration: none;
        }

        table.table td a {
            font-weight: bold;
            color: #566787;
            display: inline-block;
            text-decoration: none;
        }

        table.table td a:hover {
            color: #2196F3;
        }

        table.table td a.view {
            width: 30px;
            height: 30px;
            color: #2196F3;
            border: 2px solid;
            border-radius: 30px;
            text-align: center;
        }

        table.table td a.view i {
            font-size: 22px;
            margin: 2px 0 0 1px;
        }

        table.table .avatar {
            border-radius: 50%;
            vertical-align: middle;
            margin-right: 10px;
        }

        table {
            counter-reset: section;
        }

        .count:before {
            counter-increment: section;
            content: counter(section);
        }
    </style>

    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
