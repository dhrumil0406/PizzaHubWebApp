@if (session('userloggedin') && session('userloggedin') == true)
    @php
        $userloggedin = true;
        $userId = session('userId');
    @endphp
@else
    @php
        $userloggedin = false;
        $userId = 0;
    @endphp
@endif

@if (session('pdf_url'))
    <script>
        window.open('{{ session('pdf_url') }}', '_blank');
    </script>
@endif

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <title>Your Order</title>
    <link rel = "icon" href ="img/logo.jpg" type = "image/x-icon">
    <style>
        .footer {
            position: fixed;
            bottom: 0;
        }

        .table-wrapper {
            background: #fff;
            padding: 20px 25px;
            margin: 30px auto;
            border-radius: 3px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
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
            border-radius: 14px;
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
            background-color: #fcfcfc;
        }

        table.table-striped.table-hover tbody tr:hover {
            background: #f5f5f5;
        }

        table.table th i {
            font-size: 13px;
            margin: 0 5px;
            cursor: pointer;
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

        .table-responsive::-webkit-scrollbar {
            display: none;
        }
    </style>

</head>

<body>
    @extends('layouts.nav')
    @section('content')
        @if ($userloggedin == true)
            <div class="container">
                <div class="table-wrapper" id="empty">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-4">
                                <h2>Order <b>Details</b></h2>
                            </div>
                            <div class="col-sm-8">
                                <a href="" class="btn btn-primary"><i class="material-icons">&#xE863;</i>
                                    <span>Refresh List</span></a>
                                <a href="#" onclick="window.print()" class="btn btn-info"><i
                                        class="material-icons">&#xE24D;</i> <span>Print</span></a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-center">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Order Id</th>
                                    <th>Address</th>
                                    <th>Phone No</th>
                                    <th>Amount</th>
                                    <th>Payment Mode</th>
                                    <th>Payment Status</th>
                                    <th>Order Date</th>
                                    <th>Status</th>
                                    <th>Items</th>
                                    <th>Download Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($orders->isEmpty())
                                    <tr>
                                        <td colspan="9">
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
                                        <td>{{ substr($order->address, 0, 20) }}</td>
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
                                            $payment = App\Models\Payment::where(
                                                'paymentId',
                                                $order->paymentid,
                                            )->first();
                                        @endphp
                                        <td>
                                            @if ($payment)
                                                @if ($payment->status == 'completed')
                                                    <span class="badge badge-success" style="font-size:14px;">Paid</span>
                                                @elseif ($payment->status == 'refunded')
                                                    <span class="badge badge-warning"
                                                        style="font-size:14px;">Refunded</span>
                                                @elseif ($payment->status == 'failed')
                                                    <span class="badge badge-danger" style="font-size:14px;">Failed</span>
                                                @else
                                                    <span class="badge badge-secondary"
                                                        style="font-size:14px;">Pending</span>
                                                @endif
                                            @else
                                                <span class="badge badge-secondary" style="font-size:14px;">No Record</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->orderdate }}</td>
                                        <td><a href="" data-toggle="modal"
                                                data-target="#orderStatus{{ $order->orderid }}" class="view">
                                                <i class="material-icons">&#xE5C8;</i></a></td>
                                        <td><a href="" data-toggle="modal"
                                                data-target="#orderItem{{ $order->orderid }}" class="view"
                                                title="View Details"><i class="material-icons">&#xE5C8;</i></a></td>
                                        <td><a href="{{ route('order.download', ['orderid' => $order->orderid]) }}">⬇️</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="container" style="min-height : 610px;">
                <div class="alert alert-info my-3">
                    <font style="font-size:22px">
                        <center>Check your order. You need to
                            <strong>
                                <a class="alert-link" data-toggle="modal" data-target="#loginModal">Login</a>
                            </strong>
                        </center>
                    </font>
                </div>
            </div>
        @endif
    @endsection
    @extends('paricals.orderItemModel')
    @extends('paricals.orderStatusModel')


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>
</body>

</html>
