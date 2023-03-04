@extends('layout.siteLayout')
@section('content')
<section class="order_page stage_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="data-oder-details">
                    <div>
                        <p>Order Id</p>
                        <strong>{{ $order->id }}</strong>
                    </div>
                    <div>
                        <p>Order Date</p>
                        <strong>{{ $order->date }}</strong>
                    </div>
                    <div>
                        <p>Status</p>
                        
                           <strong class="badge badge-pill badge-{{$order->status_badge}}">
                                                 {{$order->status_text}}</strong>

                    </div>
                    <div>
                        <p>Payment Method</p>
                        <strong class="label font-weight-bold label-lg  label-light-{{$order->payment_method==1?'success':'warning'}} label-inline">{{$order->payment_method==1?__('cp.online'):__('cp.cache_on_pickup')}}
                        </strong>
                    </div>
                </div>
                <div class="cont-summary wow fadeInUp ">
                    <h5>Order Summary</h5>
                    <div class="pro-det-order">
                        @foreach($orderdetails as $orderdetail)
                        <div class="pro-order wow fadeInUp">
                            <div class="d-flex">
                                <figure><img src="{{ $orderdetail->product->image }}" alt="" /></figure>
                                <div>
                                    <p>{{ $orderdetail->product->name }} ….</p>
                                    <b>QTY : {{ $orderdetail->quantity }}</b>
                                </div>
                            </div>
                            <p><span>Add-out</span> Charger,Headphones</p>
                            <strong><span>Price</span>{{ $orderdetail->price }}</strong>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="cont-address wow fadeInUp ">
                    <h4>Delivery Address</h4>
                    <strong>{{ $address->address_name }}</strong>
                    <strong>{{ $address->building }}</strong>
                    <p>{{  $address->city->city_name}}</p>
                </div>
                <div class="tot--cart wow fadeInUp">
                    <h5>Payment Details</h5>
                    <div class="d-flex justify-content-between d-total">
                        <p>Products Total</p>
                        <p>{{ $products_total }}KWD</p>
                    </div>
                    <div class="d-flex justify-content-between d-total">
                        <p>Discount</p>
                        <p>0.000 KWD</p>
                    </div>
                    <div class="d-flex justify-content-between d-total">
                        <p>Delivery Charges</p>
                        <p>{{ @$address->city->price }}KWD</p>
                    </div>
                    <div class="d-flex justify-content-between d-total total-crt">
                        <strong>Total:</strong>
                        <strong>{{$products_total + $address->city->price  }}KWD</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection