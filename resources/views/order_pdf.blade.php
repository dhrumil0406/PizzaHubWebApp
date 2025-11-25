<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Invoice - {{ $orderDetails->orderid ?? 0 }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin-top: 30px;
        }

        .main {
            font-size: 12px;
            border: 1px solid #000;
            padding: 20px;
            width: 90%;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
        }

        .order-info,
        .user-info {
            margin-bottom: 10px;
        }

        .order-info table,
        .user-info table {
            width: 100%;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            text-align: right;
            margin-top: 5px;
            font-size: 12px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
        }

        .payment-info {
            margin-top: 10px;
            font-size: 12px;
        }

        .payment-info table {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="header">
            <h2>Pizza Hub - Order Invoice</h2>
        </div>
        <div class="order-info">
            <h4>Order Details:</h4>
            <table>
                <tr>
                    <td><strong>Order ID:</strong></td>
                    <td>{{ $orderDetails->orderid ?? 0 }}</td>
                </tr>
                <tr>
                    <td><strong>Order Date:</strong></td>
                    <td>{{ \Carbon\Carbon::parse($orderDetails->orderdate ?? 'N/A')->format('d-m-Y h:i A') }}</td>
                </tr>
                <tr>
                    <td><strong>Payment Method:</strong></td>
                    <td>
                        @if ($orderDetails->paymentmethod == 1)
                            Cash On Delivery
                        @elseif($orderDetails->paymentmethod == 2)
                            Online Payment with Card
                        @else
                            Online Payment with UPI
                        @endif
                    </td>
                </tr>
                @if ($orderDetails->paymentmethod == 2 || $orderDetails->paymentmethod == 3)
                    <tr>
                        <td><strong>Payment ID:</strong></td>
                        <td>{{ $orderDetails->paymentid ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Transaction ID:</strong></td>
                        <td>{{ $paymentDetails->transaction_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Payment Status:</strong></td>
                        <td>{{ $paymentDetails->status ?? 'N/A' }}</td>
                    </tr>
                @else
                    <tr>
                        <td><strong>Payment ID:</strong></td>
                        <td>{{ $orderDetails->paymentid ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Payment Status:</strong></td>
                        <td>Pending (Cash On Delivery)</td>
                    </tr>
                @endif
            </table>
        </div>
        <div class="user-info">
            <h4>Customer Details:</h4>
            <table>
                <tr>
                    <td><strong>Name:</strong></td>
                    <td>{{ $orderDetails->fullname ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>{{ $orderDetails->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Phone No:</strong></td>
                    <td>{{ $orderDetails->phoneno ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Address:</strong></td>
                    <td>{{ $orderDetails->address ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        <h4>Order Items:</h4>
        <table>
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Pizza / Combo Name</th>
                    <th>Quantity</th>
                    <th>Price (Rs.)</th>
                    <th>Total (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $comboGroups = [];
                    $index = 1; // only for pizzas + combo main row

                    // Group by catid
                    foreach ($orderItems as $item) {
                        if ($item->catid != 0) {
                            $comboGroups[$item->catid][] = $item;
                        } else {
                            // Normal pizza
                            $pizzaItem = App\Models\PizzaItems::find($item->pizzaid);
                @endphp
                            <tr>
                                <td>{{ $index }}</td>
                                <td>{{ $pizzaItem->pizzaname ?? 'N/A' }}</td>
                                <td>{{ $item->quantity ?? 'N/A' }}</td>
                                <td>{{ $pizzaItem->pizzaprice ?? 'N/A' }}</td>
                                <td>{{ $item->quantity * ($pizzaItem->pizzaprice ?? 0) }}</td>
                            </tr>
                @php
                            $index++;
                        }
                    }

                    // Handle combos
                    foreach ($comboGroups as $catId => $comboItems) {
                        $categoryItem = App\Models\Categories::find($catId);
                @endphp
                        <!-- Combo main row -->
                        <tr>
                            <td>{{ $index }}</td>
                            <td><strong>{{ $categoryItem->catname ?? 'Combo' }}</strong></td>
                            <td>{{ $comboItems[0]->quantity ?? 1 }}</td>
                            <td>{{ $categoryItem->comboprice ?? 'N/A' }}</td>
                            <td><strong>{{ $comboItems[0]->quantity * $categoryItem->comboprice ?? 'N/A' }}</strong></td>
                        </tr>
                @php
                        $index++;

                        // Child pizzas (no index)
                        foreach ($comboItems as $comboItem) {
                            $pizzaItem = App\Models\PizzaItems::find($comboItem->pizzaid);
                @endphp
                            <tr>
                                <td></td>
                                <td>* {{ $pizzaItem->pizzaname ?? 'N/A' }}</td>
                                <td>{{ $comboItem->quantity ?? 'N/A' }}</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                @php
                        }
                    }
                @endphp
            </tbody>
        </table>
        <div class="total">
            <p><strong>Total Final Price:</strong> ₹{{ number_format($orderDetails->totalfinalprice, 2) }}/-Rs.
            </p>
            @php
                $discountPrice = $orderDetails->totalfinalprice - $orderDetails->discountedtotalprice;
            @endphp
            <p><strong>Total Discount:</strong>
                ₹{{ number_format($discountPrice, 2) }}/-Rs.</p>
            <p><strong>Discounted Price:</strong>
                ₹{{ number_format($orderDetails->discountedtotalprice ?? 0, 2) }}/-Rs.</p>
        </div>

        <div class="footer">
            <p>Thank you for ordering from Pizza Hub!</p>
        </div>
    </div>
</body>

</html>
