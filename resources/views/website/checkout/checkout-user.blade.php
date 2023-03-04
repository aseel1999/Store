@extends('layout.siteLayout')
@section('content')
<section class="checkout_page stage_padding">
    <div class="container">
        <div class="sec_head wow fadeInUp">
            <h2>Checkout</h2>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="cont-checkout wow fadeInUp">
                    <div class="head-page">
                        <h5>MY Address</h5>
                        <a class="btn-site btnSt" data-bs-toggle="modal" data-bs-target="#modalAdderss"><span>Add Address</span></a>
                    </div>
                    <div class="list-address">
                        <div class="row">
                            @foreach($addresses as $address)
                            <div class="col-lg-6">
                                <div class="item--adress checked-address wow fadeInUp">
                                    <div class="radio-item">
                                        <input type="radio" id="{{ $address->id }}" name="adress" value="{{ $address->id }}" checked="checked">
                                        <label for="{{ $address->id }}">
                                            <h6>{{ @$address->address_name }}</h6>
                                            <p>{{ @$address->building }}</p>
                                            <p>{{  $address->city->city_name}}</p>
                                            <p>{{ @$address->city->city_name }}</p>
                                        </label>
                                    </div>

                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
           
            <div class="col-lg-4">
                @if(Session::has('promo_code'))
                    @else
                <div class="cont-promo wow fadeInUp" id="couponField">
                    <h5>Promo Code</h5>
                    <p>Have a Promo Code? Use it here</p>
                    <form class="form-promo" action="#">
                        <div class="form-group">
                            <input type="text" class="form-control" id="name"  placeholder="Enter Here">
                            <button class="btn-site" onclick="applyCoupon()"><span>Apply</span></button>
                        </div>
                    </form>
                </div>
                @endif   
                <div class="payment-method wow fadeInUp">
                    <h5>Payment Method</h5>
                    <ul class="form-check">
                        <li>
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="{{$order->payment_method=1 }}" checked="">
                            <label class="form-check-label" for="{{$order->payment_method=1 }}">{{ __('cp.online') }}</label>
                        </li>
                        <li>
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="{{$order->payment_method=2}}" checked="">
                            <label class="form-check-label" for="{{$order->payment_method=2}}">{{ __('cp.cache_on_pickup') }}</label>
                        </li>
                    </ul>
                </div>
                <div class="tot-cart wow fadeInUp" id="couponCalField">

                   <div class="proceed-checkout">
                        <a href= "#" class="btn-site"><span>Place Order</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection