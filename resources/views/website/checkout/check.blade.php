@extends('layout.siteLayout')
@section('content')
<section class="checkout_page stage_padding">
    <div class="container">
        <div class="sec_head wow fadeInUp">
            <h2>Checkout</h2>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <form class="form-checkout form-st" action="{{ route('storeUserCheckout') }}">
                    <div class="cont-checkout wow fadeInUp">
                        <h5>User Details</h5>
                        <div class="d-flex">
                            <div class="form-group">
                                <label>Full Name*</label>
                                <input type="text"  name="user_name"class="form-control" value="{{old('user_name',@$item->user_name)}}"  placeholder="Enter Here ">
                            </div>
                            <div class="form-group">
                                <label>Email Address*</label>
                                <input type="email" name="user_email"  value="{{old('user_email',@$item->user_email)}}"class="form-control" placeholder="Enter Here ">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="form-group">
                                <label>Mobail Number*</label>
                                <input type="number" name="user_phone" value="{{old('user_phone',@$item->user_phone)}}" class="form-control" placeholder="Enter Here ">
                            </div>
                        </div>
                    </div>
                    <div class="cont-checkout wow fadeInUp">
                        <h5>Shipping Address</h5>
                        <div class="d-flex">
                            <div class="form-group selectBt">
                            <label>Area*</label>
                            <select class="form-control form-control-solid"
                                                    name="country_id" id="country_id" required>
                                                <option value=""> @lang('cp.select')</option>
                                                @foreach($countries as $country)
                                                    <option value="{{$country->id}}"
                                                            data-id="{{$country->id}}"> {{$country->country_name}} </option>
                                                @endforeach
                                            </select>
                        </div>
                            <div class="form-group">
                                <label>Block*</label>
                                <input type="number"  name="block" value="{{old('block',@$item->block)}}"class="form-control" placeholder="Enter Block">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="form-group">
                                <label>Street*</label>
                                <input type="number" name="street" value="{{old('street',@$item->street)}}"class="form-control" placeholder="Enter St">
                            </div>
                            <div class="form-group selectBt">
                                <label>Accommodation Type*</label>
                                <select class="form-control" name="accomendation_type">
                                    <option
                                                    value="1" {{@$item->accomendation_type == '1'?'selected':''}}>
                                                   House
                                                </option>
                                                <option
                                                value="2" {{@$item->accomendation_type == '2'?'selected':''}}>
                                                House Srt
                                            </option>
                                            <option
                                            value="3" {{@$item->accomendation_type == '3'?'selected':''}}>
                                            GFD House
                                        </option>
                                        <option
                                        value="4" {{@$item->accomendation_type == '4'?'selected':''}}>
                                       Level House
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="form-group">
                                <label>House Number *</label>
                                <input type="number" name="house_number" value="{{old('house_number',@$item->house_number)}}"class="form-control" placeholder="Please Enter">
                            </div>
                            <div class="form-group">
                                <label>Delivery Mobile Number*</label>
                                <input type="number" naame="delivery_mobile" value="{{old('delivery_mobile',@$item->delivery_mobile)}}" class="form-control" placeholder="Please Enter">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="form-group">
                                <label>Extra Directions</label>
                                <textarea class="form-control"  name="extra_directions" value="{{old('extra_directions',@$item->extra_directions)}}"placeholder="Please Enter"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-4">
                <div class="cont-promo wow fadeInUp">
                    <h5>Promo Code</h5>
                    <p>Have a Promo Code? Use it here</p>
                    <form class="form-promo">
                        <div class="form-group">
                            <input type="text" name="promo_code_name" class="form-control" placeholder="Enter Here">
                            <button class="btn-site"><span>Apply</span></button>
                        </div>
                    </form>
                    <span class="promo-success">APPLIED SUCCESSFULLY!</span>
                    <span class="promo-invalid d-none">Invalid Promo Code!</span>
                </div>
                <div class="payment-method wow fadeInUp">
                    <h5>Payment Method</h5>
                    <ul class="form-check">
                        <li>
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="1" checked="">
                            <label class="form-check-label" for="1">{{ __('cp.online') }}</label>
                        </li>
                        <li>
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="2" checked="">
                            <label class="form-check-label" for="2">{{ __('cp.cache_on_pickup') }}</label>
                        </li>
                    </ul>             
                </div>
                <div class="tot-cart wow fadeInUp">
                    <div class="d-flex justify-content-between d-total">
                        <p>Products Total</p>
                        <p>58.000 KWD</p>
                    </div>
                    <div class="d-flex justify-content-between d-total">
                        <p>Discount</p>
                        <p>0.000 KWD</p>
                    </div>
                    <div class="d-flex justify-content-between d-total">
                        <p>Delivery Charges</p>
                        <p>1.000 KWD</p>
                    </div>
                    <div class="d-flex justify-content-between d-total total-crt">
                        <strong>Total:</strong>
                        <strong>59.000 KWD</strong>
                    </div>
                    <div class="proceed-checkout">
                        <a class="btn-site"><span>Place Order</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection