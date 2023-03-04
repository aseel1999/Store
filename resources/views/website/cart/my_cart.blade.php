@extends('layout.siteLayout')
@section('content')
<section class="cart_page stage_padding">
    <div class="container">
        <div class="sec_head wow fadeInUp">
            <h2>Cart</h2>
        </div>
        @if(Cart::count()!="0")
        <div class="content-cart wow fadeInUp">
            <div class="table-responsive-lg">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carts as $item)
                        <tr>
                            <td>
                                <div class="pdu-tb">
                                    <figure><img src="{{ @$item->product->image }}" alt="" /></figure>
                                    <div class="txt-pdu">
                                        <h5>{{ @$item->product->name }} ….</h5>
                                        <p>Color : {{ @$item->color->name_color }}</p>
                                        <p>Size : {{ @$item->size->name_size }}</p>
                                        
                                    </div>
                                </div>
                            </td>
                            <td><strong class="price-product">{{ @$item->price}}</strong></td>
                            <td>
                                <div class="quantity qty-cart">
                                    <div class="btn button-count inc jsQuantityIncrease" id="${item.rowId}"onclick="cartIncrement(this.id)">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </div>
                                    <input type="text" name="count-quat1" class="count-quat" value="1">
                                    <div class="btn button-count dec jsQuantityDecrease disabled" minimum="1">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="total-price">
                                    <p>{{$item->price * $item->quantity}} KWD</p>
                                </div>
                            </td>
                            <td>
                                <a class="remove-tb"><i class="fa-solid fa-trash-can"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @php
           // $sub_total=App\Models\Cart::total();
            @endphp
            <div class="tot-cart">
                <div class="d-flex justify-content-between d-total">
                    <strong>Sub Total :</strong>
                    <strong>KWD</strong>
                </div>
                <div class="proceed-checkout">
                    <a href="proceed-checkout.html" class="btn-site"><span>Proceed To Checkout</span></a>
                </div>
            </div>
        </div>
        @else
        <div class="empty d-none wow fadeInUp">
            <div class="item-empty">
                <p>No items in cart (Add items)</p>
                <span>We Have Found No Items Added In Your Cart To Checkout</span>
                <a class="btn-site"><span>Add Items</span></a>
            </div>
        </div>
        @endif
    </div>
</section>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
