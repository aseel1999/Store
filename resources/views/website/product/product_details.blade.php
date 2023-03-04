@extends('layout.siteLayout')
@section('content')
<section class="product_details_page stage_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="block-product-slide">
                    <div class="slider slider-for clearfix">
                        @foreach($products as $product)
                        <div class="pro-slide-item">
                            <div class="pro--Thumb slider-for__item ex1" data-src="images/c1.png">
                                <img src="{{ $product->image }}" alt="" />
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="slider slider-nav clearfix">
                        @foreach($products as $product)
                        <div class="sp-nav">
                            <img src="{{ $product->image }}" alt="" class="img-responsive">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @php
            $amount = ($product->price * $product->discount)/100;
            $sale_price = $product->price - $amount;
        
            @endphp
            <div class="col-lg-6">
                <div class="data-product">
                    <div class="name-product">
                        <h5>{{ $product->name }} <br /> circle ….</h5>
                        <p>{{ @$product->brand->brand_name }}</p>
                        <ul>
                            <li>@if($product->discount != NULL)<p>{{$sale_price }}KWD</p>@endif</li>
                            <li><del>{{ $product->price }}KWD</del></li>
                        </ul>
                    </div> 
                    <div class="pdp--list">
                        <div class="pdp-ro">
                            <strong>Color </strong>
                            <div class="color-choose">
                                @foreach($product->colors as  $color)
                                <div>
                                    <input data-image="color1" type="radio" id="{{ $color->name_color }}" name="name-color" value="{{ $color->name_color }}">
                                    <label for="{{ $color->name_color }}"><span style="background:{{ $color->name_color }}"></span></label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="pdp-ro">
                            <strong>Size</strong>
                            <div class="size-pro">
                               @foreach($product->sizes as $size)
                                <div>
                                    <input data-image="" type="radio" id="{{ $size->name_size }}" name="name_size" value="{{ $size->name_size }}">
                                    <label for="{{ $size->name_size }}">
                                      <span>{{ $size->name_size }}</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="opt-produ">
                        <div class="add-cart">
                            <a href="{{ route('cart.store') }}" type="submit" class="button button-add-to-cart"><span><i class="icon-cart"></i>Add to cart</span></a>
                        </div>
                        <div class="quantity">
                            <div class="btn button-count dec jsQuantityDecrease disabled" minimum="1"><i class="fa fa-minus" aria-hidden="true"></i></div>
                            <input type="number" name="num_product" class="count-quat" id="countIncrease" value="1">
                            <div class="btn button-count inc jsQuantityIncrease"><i class="fa fa-plus" aria-hidden="true"></i></div>
                        </div>
                    </div>
                    <div class="product-description">
                        <h5>Product Description</h5>
                        <p>{{ $product->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
<script>
        
    new WOW().init();
    
    $('.ex1').zoom();
    
</script>
<script>
    $('.slider-for').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      fade: true,
      asNavFor: '.slider-nav'
    });
    $('.slider-nav').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      asNavFor: '.slider-for',
      dots: false,
      centerMode: true,
      focusOnSelect: true,
        responsive: [
        {
          breakpoint: 600,
          settings: {
              slidesToShow: 3,
              centerMode: true,
          }
        }
        ],
        cssEase: 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
        useTransform: true,
        arrows: true,
        prevArrow: '<button class="slide-arrow prev-arrow"><i class="fa fa-angle-left"></i></button>',
        nextArrow: '<button class="slide-arrow next-arrow"><i class="fa fa-angle-right"></i></button>'
    });
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script>
    $(document).ready(function(){
    var count = 1;

    $(".jsQuantityIncrease").click(function() {
    count++;
    alert(count);
    $("#countIncrease").val(count);
});
});
</script>
