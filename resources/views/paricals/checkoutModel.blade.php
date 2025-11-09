@if (session('userloggedin') && session('userloggedin') == true)
    @php
        $userloggedin = true;
        $usertype = session('usertype');
    @endphp
@else
    @php
        $userloggedin = false;
        $usertype = 0;
    @endphp
@endif

@if ($userloggedin)
    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-light" style="background: #4b5366;">
                    <h5 class="modal-title" id="checkoutModal">Enter Your Details: </h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        $user = DB::table('users_admins')->where('userid', session('userId'))->first();
                    @endphp

                    <form action="{{ route('user.checkout') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <b><label for="fullname">FullName:</label></b>
                            <input class="form-control" id="fullname" name="fullname" placeholder="Your Name"
                                type="text" value="{{ old('fullname', $user->firstname . ' ' . $user->lastname) }}">
                        </div>
                        <div class="form-group">
                            <b><label for="email">Email:</label></b>
                            <input class="form-control" id="email" name="email" placeholder="example@gmail.com"
                                type="email" value="{{ old('email', $user->email) }}">
                        </div>
                        <div class="form-group">
                            <b><label for="address">Address:</label></b>
                            <input class="form-control" id="address" name="address" placeholder="1234, street, city"
                                type="text" value="{{ old('address') }}">
                        </div>
                        <div class="form-group">
                            <b><label for="phoneNo">Phone No:</label></b>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon">+91</span>
                                </div>
                                <input type="tel" class="form-control" id="phoneNo" name="phoneNo"
                                    value="{{ old('phoneNo', $user->phoneno) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <b><label for="password">Password:</label></b>
                            <input class="form-control" id="password" name="password" placeholder="Enter Password"
                                type="password" data-toggle="password">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            @if (session('paymentMethod') == 2)
                                {{-- <form method="POST" action="{{ route('stripe.initiate') }}"> --}}
                                <form method="POST" action="{{ route('user.checkout') }}">
                                    @csrf
                                    <input type="hidden" name='_token' value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-success">Pay with Card (Stripe)</button>
                                </form>
                            @else
                                <button type="submit" name="checkout" class="btn btn-success">Order</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @yield('checkoutModel')
@endif
