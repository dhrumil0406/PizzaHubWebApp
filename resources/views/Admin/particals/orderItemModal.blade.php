@foreach ($orders as $order)
    <!-- Modal -->
    <div class="modal fade" id="orderItem{{ $order->orderid }}" tabindex="-1" role="dialog"
        aria-labelledby="orderItem{{ $order->orderid }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-light" style="background: #4b5366;">
                    <h5 class="modal-title" id="orderItem{{ $order->orderid }}">
                        Order Items — Order Id: {{ $order->orderid }}
                    </h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="container">
                        <div class="row">

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="border-0 bg-light">
                                                <h4 class="text-center" style="font-family: amerika">Pizza Items</h4>
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @php
                                            // Get all items of this order
                                            $orderItems = App\Models\OrderItem::where(
                                                'orderid',
                                                $order->orderid,
                                            )->get();

                                            // Group by catid (combo group)
                                            $groupedItems = $orderItems->groupBy('catid');
                                        @endphp

                                        @foreach ($groupedItems as $catid => $items)
                                            @php
                                                $firstItem = $items->first();
                                                $qty = $firstItem->quantity;

                                                // SIMPLE PIZZA (no combo)
                                                if (!$catid) {
                                                    $pizza = App\Models\PizzaItems::find($firstItem->pizzaid);

                                                    $price = $pizza->pizzaprice;
                                                    $total = $price * $qty;
                                                    $discounted = $total - ($total * $firstItem->discount) / 100;
                                                }
                                                // COMBO ITEM
                                                else {
                                                    $combo = App\Models\Categories::find($catid);

                                                    $price = $combo->comboprice;
                                                    $total = $price * $qty;
                                                    $discounted = $total - ($total * $firstItem->discount) / 100;

                                                    // Fetch all pizzas inside combo
                                                    $comboItems = App\Models\PizzaItems::where('catid', $catid)->pluck(
                                                        'pizzaname',
                                                    );
                                                }
                                            @endphp

                                            <tr>
                                                <td colspan="2">

                                                    <!-- CARD START -->
                                                    <div class="card shadow-sm mb-3" style="border-radius: 14px;">
                                                        <div class="card-body">

                                                            <div class="d-flex">

                                                                <!-- IMAGE -->
                                                                <img src="{{ $catid ? '/catimages/' . $combo->catimage : '/pizzaimages/' . $pizza->pizzaimage }}"
                                                                    width="80" height="80"
                                                                    style="border-radius: 12px; object-fit: cover;">

                                                                <div class="ml-3">

                                                                    <!-- TITLE -->
                                                                    <h5 class="mb-1 font-weight-bold">
                                                                        {{ $catid ? $combo->catname : $pizza->pizzaname }}
                                                                    </h5>

                                                                    <!-- PRICE + DISCOUNT -->
                                                                    <div class="d-flex align-items-center">

                                                                        <p class="mb-1 text-muted">
                                                                            {{ $catid ? 'Combo Price:' : 'Price:' }}
                                                                            ₹{{ number_format($price, 2) }}
                                                                        </p>

                                                                        <p class="mx-2 mb-1">|</p>

                                                                        <p class="mb-1">
                                                                            <span class="text-muted">Discounted
                                                                                Price:</span>
                                                                            <span class="text-success font-weight-bold">
                                                                                ₹{{ number_format($discounted, 2) }}
                                                                            </span>
                                                                        </p>

                                                                    </div>

                                                                    <!-- QTY -->
                                                                    <p class="mb-1">
                                                                        Qty: <strong>{{ $qty }}</strong>
                                                                    </p>

                                                                </div>
                                                            </div>

                                                            <!-- COMBO ITEMS LIST -->
                                                            @if ($catid)
                                                                <hr>
                                                                <div>
                                                                    @foreach ($comboItems as $ci)
                                                                        <p class="mb-1">🍕 {{ $ci }}</p>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                        </div>
                                                    </div>
                                                    <!-- CARD END -->

                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endforeach

@yield('orderItemModel')
