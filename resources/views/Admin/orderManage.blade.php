<link rel = "icon" href ="/img/logo.jpg" type = "image/x-icon">

<body id="body-pd" style="background: #80808045;">
    @extends('admin.layouts.nav')
    @section('content')
        <div class="container" style="margin-top:98px; background: aliceblue;">
            <div class="table-wrapper">
                <div class="table-title" style="border-radius: 14px;">
                    <div class="row">
                        <div class="col-sm-4">
                            <h2>Order <b>Details</b></h2>
                        </div>
                        <div class="col-sm-8">
                            <a href="" class="btn btn-primary"><i class="material-icons">&#xE863;</i> <span>Refresh
                                    List</span></a>
                            <a href="#" onclick="window.print()" class="btn btn-info"><i
                                    class="material-icons">&#xE24D;</i> <span>Print</span></a>
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
                                <th>
                                    <a href="?sort=orderid&order={{ $sort === 'orderid' ? $order : 'asc' }}">
                                        Order Id
                                        @if ($sort === 'orderid')
                                            {{ request('order') === 'asc' ? '↑' : '↓' }}
                                        @endif
                                    </a>
                                </th>
                                <th>User Id</th>
                                <th>
                                    <a href="?sort=address&order={{ $sort === 'address' ? $order : 'asc' }}">
                                        Address
                                        @if ($sort === 'address')
                                            {{ request('order') === 'asc' ? '↑' : '↓' }}
                                        @endif
                                    </a>
                                </th>
                                <th>Phone No</th>
                                <th>Amount</th>
                                <th>
                                    <a href="?sort=paymentmethod&order={{ $sort === 'paymentmethod' ? $order : 'asc' }}">
                                        Payment Mode
                                        @if ($sort === 'paymentmethod')
                                            {{ request('order') === 'asc' ? '↑' : '↓' }}
                                        @endif
                                    </a>
                                </th>
                                <th>Payment Status</th>
                                <th>
                                    <a href="?sort=orderdate&order={{ $sort === 'orderdate' ? $order : 'asc' }}">
                                        Order Date
                                        @if ($sort === 'orderdate')
                                            {{ request('order') == 'asc' ? '↑' : '↓' }}
                                        @endif
                                    </a>
                                </th>
                                <th>Status</th>
                                <th>Items</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->isEmpty())
                                <tr>
                                    <td colspan="12">
                                        <div class="alert alert-info my-3">
                                            <font style="font-size:22px">
                                                <center>No Orders Found</center>
                                            </font>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @foreach ($orders as $order)
                                <tr style="font-size: 14px;">
                                    <td>{{ $order->orderid }}</td>
                                    <td>{{ $order->userid }}</td>
                                    <td data-toggle="tooltip" title="{{ $order->address }}">
                                        {{ substr($order->address, 0, 20) }}
                                    </td>
                                    <td>{{ $order->phoneno }}</td>
                                    <td>Rs.{{ $order->discountedtotalprice }}/-</td>
                                    <td>
                                        @if ($order->paymentmethod == 1)
                                            Cash
                                        @elseif ($order->paymentmethod == 2)
                                            Card
                                        @elseif ($order->paymentmethod == 3)
                                            UPI
                                        @endif
                                    </td>
                                    @php
                                        $payment = App\Models\Payment::where('paymentId', $order->paymentid)->first();
                                    @endphp
                                    <td>
                                        @if ($payment)
                                            @if ($payment->status == 'completed')
                                                <span class="badge badge-success" style="font-size:14px;">Paid</span>
                                            @elseif ($payment->status == 'refunded')
                                                <span class="badge badge-warning" style="font-size:14px;">Refunded</span>
                                            @elseif ($payment->status == 'failed')
                                                <span class="badge badge-danger" style="font-size:14px;">Failed</span>
                                            @else
                                                <span class="badge badge-secondary" style="font-size:14px;">Pending</span>
                                            @endif
                                        @else
                                            <span class="badge badge-secondary" style="font-size:14px;">No Record</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->orderdate }}</td>
                                    <td><a href="" data-toggle="modal"
                                            data-target="#orderStatus{{ $order->orderid }}" class="view">
                                            <i class="material-icons">&#xE5C8;</i></a></td>
                                    <td><a href="" data-toggle="modal" data-target="#orderItem{{ $order->orderid }}"
                                            class="view" title="View Details"><i class="material-icons">&#xE5C8;</i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @extends('admin.particals.orderItemModal')
        @extends('admin.particals.orderStatusModal')
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

        table.table tr th,
        table.table tr td {
            border-color: #e9e9e9;
            padding: 12px 15px;
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
