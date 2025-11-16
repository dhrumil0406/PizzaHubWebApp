<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Validator;
use App\Models\DeliveryDetails;
use App\Models\Categories;
use App\Models\UsersAdmin;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PizzaItems;
use App\Models\PizzaCart;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use PSpell\Config;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;


use function PHPUnit\Framework\assertNotEmpty;

class CartController extends Controller
{
    public function showCart()
    {
        if (session('userloggedin') && session('userloggedin') == true) {
            $userId = session('userId');
            $cartItems = PizzaCart::where('userid', $userId)->get();
            return view('viewcart', compact('cartItems'));
        } else {
            $cartItems = [];
            return view('viewcart', compact('cartItems'));
            // return back()->with('error', 'Please log in to view your cart.');
        }
    }

    public function addToCart()
    {
        if (session('userloggedin') && session('userloggedin') == true) {
            $userId = session('userId');
        } else {
            return back()->with('error', 'Please log in to add items to cart.');
        }
        $pizzaid = request('pizzaid');
        $cartItem = PizzaCart::where('userid', $userId)->where('pizzaid', $pizzaid)->where('catid', null)->first();

        if ($cartItem) {
            return back()->with('error', 'Item already added!');
        } else {
            PizzaCart::create([
                'pizzaid' => $pizzaid,
                'catid' => null,
                'userid' => $userId,
                'quantity' => 1,
                'itemadddate' => Carbon::now('Asia/Kolkata'),
            ]);
        }
        return back()->with('success', 'Item added to cart successfully!');
    }

    public function addToCart2()
    {
        if (session('userloggedin') && session('userloggedin') == true) {
            $userId = session('userId');
        } else {
            return back()->with('error', 'Please log in to add items to cart.');
        }
        $catid = request('catid');
        $cartItem = PizzaCart::where('userid', $userId)->where('pizzaid', 1)->where('catid', $catid)->first();

        if ($cartItem) {
            return back()->with('error', 'Item already added!');
        } else {
            PizzaCart::create([
                'pizzaid' => 1,
                'catid' => $catid,
                'userid' => $userId,
                'quantity' => 1,
                'itemadddate' => Carbon::now('Asia/Kolkata'),
            ]);
        }
        return back()->with('success', 'Item added to cart successfully!');
    }

    public function removeFromCart($cartitemid)
    {
        if (session('userloggedin') && session('userloggedin') == true) {
            $userId = session('userId');
        } else {
            return back()->with('error', 'Please log in to remove items from cart.');
        }

        $cartItem = PizzaCart::where('userid', $userId)->where('cartitemid', $cartitemid)->first();
        if ($cartItem) {
            $cartItem->delete();
            return back()->with('success', 'Item removed from cart successfully!');
        } else {
            return back()->with('error', 'Item not found in cart!');
        }
    }

    public function clearCart()
    {
        if (session('userloggedin') && session('userloggedin') == true) {
            $userId = session('userId');
        } else {
            return back()->with('error', 'Please log in to clear the cart.');
        }

        PizzaCart::where('userid', $userId)->delete();
        return back()->with('success', 'Cart cleared successfully!');
    }

    public function updateQuantity(Request $request)
    {
        $cartItem = PizzaCart::find($request->cartitemid);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $itemTotal = $cartItem->pizza->price * $cartItem->quantity;
        $discount = $cartItem->pizza->discount;
        $finalPrice = $itemTotal - ($itemTotal * $discount / 100);

        return response()->json([
            'itemTotal' => number_format($itemTotal, 2),
            'finalPrice' => number_format($finalPrice, 2),
            'hasDiscount' => $discount > 0,
        ]);
    }

    /************************* checkout orders *************************/

    public function showCheckoutModal(Request $request)
    {
        $paymentMethod = $request->paymentMethod;

        session(['paymentMethod' => $paymentMethod]);

        return redirect()->back()->with('showModal', true);
    }

    public function checkout(Request $request)
    {
        if (session('userloggedin') && session('userloggedin') == true) {
            $userId = session('userId');
        }

        $user = UsersAdmin::find($userId);
        do {
            $orderId = 'O' . rand(1000, 9999); // 4-digit number
            $exists = Order::where('orderid', $orderId)->exists();
        } while ($exists);

        $fullname = $request->fullname;
        $email = $request->email;
        $address = $request->address;
        $phoneNo = $request->phoneNo;
        $totalFinalPrice = session('totalFinalPrice');
        $discountedTotalPrice = session('discountedTotalPrice');
        $paymentMethod = session('paymentMethod');
        $orderStatus = 1;
        $orderDate = Carbon::now('Asia/Kolkata');
        $password = $request->password;

        if (password_verify($password, $user->password)) {
            if ($paymentMethod == 1) {
                $order = Order::create([
                    'orderid' => $orderId,
                    'userid' => $userId,
                    'fullname' => $fullname,
                    'email' => $email,
                    'address' => $address,
                    'phoneno' => $phoneNo,
                    'totalfinalprice' => $totalFinalPrice,
                    'discountedtotalprice' => $discountedTotalPrice,
                    'paymentmethod' => $paymentMethod,
                    'orderstatus' => $orderStatus,
                    'orderdate' => $orderDate,
                ]);

                if ($order) {
                    $cartItems = PizzaCart::where('userid', $userId)->get();
                    foreach ($cartItems as $item) {
                        if ($item->catid) {
                            $cats = Categories::find($item->catid);
                            $pizzas = PizzaItems::where('catid', $item->catid)->get();
                            $discount = $cats->discount;
                            foreach ($pizzas as $pizza) {
                                OrderItem::create([
                                    'orderid' => $orderId,
                                    'pizzaid' => $pizza->pizzaid,
                                    'catid' => $item->catid,
                                    'quantity' => $item->quantity,
                                    'discount' => $discount,
                                ]);
                            }
                        } else {
                            $pizza = PizzaItems::find($item->pizzaid);
                            $discount = $pizza->discount;
                            OrderItem::create([
                                'orderid' => $orderId,
                                'pizzaid' => $item->pizzaid,
                                'catid' => 0,
                                'quantity' => $item->quantity,
                                'discount' => $discount,
                            ]);
                        }
                    }
                    PizzaCart::where('userid', $userId)->delete();

                    session(['orderId' => $orderId]);
                    $orderDetails = Order::where('orderid', $orderId)->first();
                    $orderItems = OrderItem::where('orderid', $orderId)->get();
                    $pdf = PDF::loadView('order_pdf', compact('orderDetails', 'orderItems'));
                    $pdf->save(public_path('invoices/Order_' . $orderId . '.pdf'));

                    return redirect()->route('user.viewOrder')->with('success', 'Order placed successfully!')->with('pdf_url', asset('invoices/Order_' . $orderId . '.pdf'));
                } else {
                    return back()->with('error', 'Order Failed Try Again!');
                }
            } elseif ($paymentMethod == 2) {
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                try {
                    $session = StripeSession::create([
                        'line_items' => [
                            [
                                'price_data' =>  [
                                    'currency' => 'inr',
                                    'product_data' => [
                                        'name' => 'Pizza Order ' . $orderId,
                                    ],
                                    'unit_amount' => $discountedTotalPrice,
                                ],
                                'quantity' => 1,
                            ],
                        ],
                        'mode' => 'payment',
                        'success_url' => 'http://127.0.0.1:8000?session_id={CHECKOUT_SESSION_ID}',
                        'cancel_url' => 'http://127.0.0.1:8000?session_id={CHECKOUT_SESSION_ID}',
                    ]);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Stripe error: ' . 'Payment could not be processed.!');
                }

                return redirect()->away('$session->url');
                // return back()->with('success', 'online payment!');
            }
        } else {
            return back()->with('error', 'Password is incorrect!');
        }
    }

    public function stripeCancel()
    {
        return redirect()->back()->with('error', 'Payment was cancelled. Your order has not been placed.');
    }

    public function orderDownload($orderid)
    {
        $orderDetails = Order::where('orderid', $orderid)->first();
        $orderItems = OrderItem::where('orderid', $orderid)->get();
        $paymentDetails = Payment::where('orderid', $orderid)->first();
        $pdf = PDF::loadView('order_pdf', compact('orderDetails', 'orderItems', 'paymentDetails'));
        return $pdf->download('Order_' . $orderid . '.pdf');
    }

    public function initiateStripePayment(Request $request)
    {
        if (session('userloggedin') && session('userloggedin') == true) {
            $userId = session('userId');
        }

        $user = UsersAdmin::find($userId);
        do {
            $orderId = 'O' . rand(1000, 9999); // 4-digit number
            $exists = Order::where('orderid', $orderId)->exists();
        } while ($exists);
        session(['orderId' => $orderId]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $totalFinalPrice = session('totalFinalPrice');
        $amountInCents = $totalFinalPrice * 100; // Stripe uses cents

        try {
            $checkout_session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'inr',
                        'product_data' => [
                            'name' => 'Pizza Order ' . $orderId,
                        ],
                        'unit_amount' => $amountInCents,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Stripe error: ' . $e->getMessage());
        }
        return redirect($checkout_session->url);
    }

    public function stripeSuccess(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $sessionId = $request->get('session_id');
        try {
            $session = StripeSession::retrieve($sessionId);
            return redirect()->route('user.orders')->with('success', 'Payment successful! Your order has been placed.');
        } catch (\Exception $e) {
            return redirect()->route('user.index')->with('error', 'Stripe session error: ' . $e->getMessage());
        }
    }

    public function setPaymentMethod(Request $request)
    {
        session(['paymentMethod' => $request->paymentMethod]);
        return response()->json(['success' => true]);
    }

    public function viewOrders()
    {
        if (session('userloggedin') && session('userloggedin') == true) {
            $userId = session('userId');
            $orders = Order::where('userid', $userId)->orderBy('orderdate', 'desc')->get();
            return view('viewOrder', compact('orders'));
        } else {
            $orders = [];
            return view('viewOrder', compact('orders'));
        }
    }

    public function manageOrders(Request $request)
    {
        $sort = $request->input('sort', 'orderdate'); // default sort column
        $order = $request->input('order', 'asc');   // default order

        $allowedSorts = ['userid', 'address', 'paymentmethod', 'orderdate'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'orderdate'; // default sort column
        }

        $orders = Order::orderBy($sort, $order)->get();
        return view('admin.orderManage', compact('orders'));

        // $orders = Order::orderBy('orderdate')->get();
        // return view('admin.orderManage', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $orderid)
    {
        $order = Order::where('orderid', $orderid)->first();

        if (!$order) {
            return back()->with('error', 'Order id : ' . $orderid . ' not found!');
        }

        // UPDATE ORDER STATUS
        $orderStatus = $request->orderstatus;
        $order->orderstatus = $orderStatus;
        $order->save();

        // HANDLE PAYMENT STATUS LOGIC
        $payment = Payment::where('paymentId', $order->paymentid)->first();

        if ($payment) {

            // 5 = Delivered/Completed
            if ($orderStatus == 5) {
                $payment->status = "completed";  // Paid
            }

            // 6 = Cancelled | Denied
            else if ($orderStatus == 6) {

                // Online Payments (UPI / Card)
                if ($order->paymentmethod == 2 || $order->paymentmethod == 3) {
                    $payment->status = "refunded";  // Auto refund
                }

                // Cash on Delivery cancelled
                else {
                    $payment->status = "failed";  // Cash not received
                }
            }

            // Other statuses
            else{
                $payment->status = "pending";  // Reset to pending for other statuses
            }

            $payment->save();
        }

        return back()->with('success', "Order ID : $orderid updated successfully!");
    }


    // public function updatePaymentStatus(Request $request, $orderid)
    // {
    //     $order = Order::where('orderid', $orderid)->first();
    //     if ($order) {
    //         $orderStatus = $request->orderstatus;
    //         $payment = Payment::where('paymentId', $order->paymentid)->first();
    //         if ($payment) {
    //             if ($orderStatus == 5) {
    //                 $payment->status = "completed"; // Paid
    //             }else if( $orderStatus == 6){
    //                 if($order->paymentmethod == 2 || $order->paymentmethod == 3){
    //                     $payment->status = "refunded"; // Refunded
    //                 }else{
    //                     $payment->status = "failed"; // cash payment failed
    //                 }
    //             }
    //             $payment->save();
    //             return back()->with('success', 'Payment status for Order id : ' . $orderid . ' updated successfully!');
    //         }else{
    //             return back()->with('error', 'Payment record for Order id : ' . $orderid . ' not found!');
    //         }
    //     } else {
    //         return back()->with('error', 'Order id : ' . $orderid . ' not found!');
    //     }
    // }

    public function updateDeliveryBoy(Request $request, $orderid)
    {
        do {
            $trackId = 'TRACK' . rand(1000, 9999);
            $exists = DeliveryDetails::where('trackid', $trackId)->exists();
        } while ($exists);

        $deliveryDetail = DeliveryDetails::where('orderid', $orderid)->first();
        if ($deliveryDetail) {
            $deliveryDetail->dbid = $request->dbid;
            $deliveryDetail->deliverytime = $request->time;
            $deliveryDetail->save();

            if ($deliveryDetail) {
                return back()->with('success', 'Order delivery details updated.');
            } else {
                return back()->with('error', 'Order delivery fail.');
            }
        } else {
            $deliveryDetail = DeliveryDetails::create([
                'orderid' => $orderid,
                'dbid' => $request->dbid,
                'deliverytime' => $request->time,
                'trackid' => $trackId,
                'deliverydate' => Carbon::now('Asia/Kolkata')
            ]);

            if ($deliveryDetail) {
                $order = Order::where('orderid', $orderid)->first();
                $order->orderstatus = 4;
                $order->save();
                return back()->with('success', 'Order out of delivery.');
            } else {
                return back()->with('error', 'Order delivery fail.');
            }
        }
    }
}
