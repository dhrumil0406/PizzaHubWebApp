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
                                            $comboGroups = [];
                                            $normalItems = [];

                                             $orderItems = App\Models\OrderItem::where(
                                                'orderid',
                                                $order->orderid,
                                            )->get();

                                            // Divide items into normal pizzas and combos (same as PDF)
                                            foreach ($orderItems as $item) {
                                                if ($item->catid != 0) {
                                                    $comboGroups[$item->catid][] = $item;
                                                } else {
                                                    $normalItems[] = $item;
                                                }
                                            }
                                        @endphp

                                        {{-- ============================= --}}
                                        {{-- NORMAL PIZZAS (same logic as PDF) --}}
                                        {{-- ============================= --}}
                                        @foreach ($normalItems as $item)
                                            @php
                                                $pizza = App\Models\PizzaItems::find($item->pizzaid);
                                                $qty = $item->quantity;

                                                $price = $pizza->pizzaprice;
                                                $total = $price * $qty;
                                            @endphp

                                            <tr>
                                                <td colspan="2">

                                                    <div class="card shadow-sm mb-3" style="border-radius: 14px;">
                                                        <div class="card-body">

                                                            <div class="d-flex">

                                                                {{-- IMAGE --}}
                                                                <img src="/pizzaimages/{{ $pizza->pizzaimage }}"
                                                                    width="80" height="80"
                                                                    style="border-radius: 12px; object-fit: cover;">

                                                                <div class="ml-3">

                                                                    {{-- TITLE --}}
                                                                    <h5 class="mb-1 font-weight-bold">
                                                                        {{ $pizza->pizzaname }}</h5>

                                                                    {{-- PRICE --}}
                                                                    <p class="mb-1 text-muted">
                                                                        Price: ₹{{ number_format($price, 2) }}
                                                                    </p>

                                                                    {{-- QTY --}}
                                                                    <p class="mb-1">Qty:
                                                                        <strong>{{ $qty }}</strong></p>

                                                                    {{-- TOTAL --}}
                                                                    <p class="mb-1">
                                                                        Total:
                                                                        <strong>₹{{ number_format($total, 2) }}</strong>
                                                                    </p>

                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        @endforeach


                                        {{-- ============================= --}}
                                        {{-- COMBO ITEMS (same logic as PDF) --}}
                                        {{-- ============================= --}}
                                        @foreach ($comboGroups as $catId => $comboItems)
                                            @php
                                                $combo = App\Models\Categories::find($catId);
                                                $qty = $comboItems[0]->quantity;

                                                $price = $combo->comboprice;
                                                $total = $price * $qty;

                                                // Child pizzas of this combo
                                                $childPizzas = App\Models\PizzaItems::where('catid', $catId)->get();
                                            @endphp

                                            <tr>
                                                <td colspan="2">

                                                    <div class="card shadow-sm mb-3" style="border-radius: 14px;">
                                                        <div class="card-body">

                                                            <div class="d-flex">

                                                                {{-- COMBO IMAGE --}}
                                                                <img src="/catimages/{{ $combo->catimage }}"
                                                                    width="80" height="80"
                                                                    style="border-radius: 12px; object-fit: cover;">

                                                                <div class="ml-3">

                                                                    {{-- COMBO TITLE --}}
                                                                    <h5 class="mb-1 font-weight-bold">
                                                                        {{ $combo->catname }}</h5>

                                                                    {{-- PRICE --}}
                                                                    <p class="mb-1 text-muted">Combo Price:
                                                                        ₹{{ number_format($price, 2) }}</p>

                                                                    {{-- QTY --}}
                                                                    <p class="mb-1">Qty:
                                                                        <strong>{{ $qty }}</strong></p>

                                                                    {{-- TOTAL --}}
                                                                    <p class="mb-1">
                                                                        Total:
                                                                        <strong>₹{{ number_format($total, 2) }}</strong>
                                                                    </p>

                                                                </div>

                                                            </div>

                                                            {{-- CHILD PIZZAS --}}
                                                            <hr>
                                                            <div>
                                                                @foreach ($childPizzas as $ci)
                                                                    <p class="mb-1">🍕 {{ $ci->pizzaname }}</p>
                                                                @endforeach
                                                            </div>

                                                        </div>
                                                    </div>

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
