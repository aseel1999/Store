<!DOCTYPE html>
<html lang="en" dir="ltr">

@include('admin.layouts.head')

<body>

    <div class="main-wrapper">

        @include('admin.layouts.header')
        <!--header-->

        <section class="section_home">
            <div class="owl-carousel" id="slide-home">
                <div class="item" style="background: url(images/slide1.jpg)"></div>
                <div class="item" style="background: url(images/slide2.jpg)"></div>
            </div>
        </section>
        
        <!--section_home-->

        <section class="section_categories">
            <div class="container">
                <div class="owl-carousel" id="categoris-slider">
                    @foreach($categories as $category)
                    <div class="item">
                        <div class="item_categoris">
                            <a href="products.html">
                                <figure><img src="{{ $category->image }}" alt=""></figure>
                                <p>{{ $category->name }}</p>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!--section_categories-->

        <section class="section_products">
            <div class="container">
                <div class="sec_head wow fadeInUp">
                    <h2>Latest Products</h2>
                    <p>Lorem Ipsum Is Simply Dummy Text Of The Printing.</p>
                </div>
                <div class="row">
                    @foreach($products as $product)
                     @php
        $first = \Carbon\Carbon::create($product->created_at);
        $second = \Carbon\Carbon::create($product->updated_at);
        $diff = now()->between($first, $second);
    @endphp
                    <div class="col-lg-4 col-6">
                        <div class="item-prod wow fadeInUp">
                            <figure><img src="{{ $product->image }}" alt="Image Product"></figure>
                            <div class="txt-prod">
                                <h5>{{ $product->name }}</h5>
                                <p>{{ @$product->brand->brand_name }}</p>
                                <ul>
                                    <li><strong>{{ (($product->price*$product->discount)/100-$product->price )}}</strong></li>
                                    <li><del>{{ $product->price }}</del></li>
                                </ul>
                                <a class="btn-site"><span>Add to Cart</span></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="view-all">
                    <i class="fa-solid fa-spinner"></i>
                </div>
            </div>
        </section>
        <!--section_products-->

        @include('admin.layouts.footer')
        <!--footer-->

    </div>
    <!--main-wrapper-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="{{ url('web/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('web/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('web/js/all.min.js') }}"></script>
    <script src="{{ url('web/js/owl.carousel.min.js') }}"></script>
    <script src="{{ url('web/js/wow.js') }}"></script>
    <script src="{{ url('web/js/jquery.easing.min.js') }}"></script>
    <script src="{{ url('web/js/script.js') }}"></script>
    <script>
        new WOW().init();
    </script>


</body>

</html>