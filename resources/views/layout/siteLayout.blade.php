<!DOCTYPE html>
<html lang="{{app()->getLocale()}}" dir="{{(app()->getLocale() == 'ar') ? 'rtl' : 'ltr'}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    @include('admin.layouts.head')
<body>
    @php
    $countries=App\Models\Country::get();
    $cities=App\Models\City::get();
    $address=App\Models\Address::first();
    
    @endphp
<div class="main-wrapper">
    @include('admin.layouts.header')
    
    <!--header-->
  
        
           
            @yield('content')
            
   
   @include('admin.layouts.footer')
   @include('website.address.modal2')
   @include('website.address.model')
   @include('website.address.delete_model')
   
   

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script type="text/javascript">
    
        function applyCoupon(){
          var name = $('#name').val();
                  $.ajax({
                      type: "POST",
                      dataType: 'json',
                      data: {name:name},
                      url: "/coupon-apply",
                      success:function(data){
                          if (data.validity == true) {
                            $('#couponField').hide();
                          }
                          const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000 
            })
            if ($.isEmptyObject(data.error)) {
                    
                    Toast.fire({
                    type: 'success',
                    icon: 'success', 
                    title: data.success, 
                    })

            }else{
               
           Toast.fire({
                    type: 'error',
                    icon: 'error', 
                    title: data.error, 
                    })
                }
                
                      }
                  })
              }
     function couponCalculation(){
        $.ajax({
            type: 'GET',
            url: "/coupon-calculation",
            dataType: 'json',
            success:function(data){
            if (data.total) {
                $('#couponCalField').html(
                    ` <div class="d-flex justify-content-between d-total">
                        <p>Products Total</p>
                        <p>${data.total} KWD</p>
                    </div>
                 
                    <div class="d-flex justify-content-between d-total">
                        <p>Discount</p>
                        <p> 000KWD</p>
                    </div>
                    <div class="d-flex justify-content-between d-total">
                        <p>Delivery Charges</p>
                        <p>${data.total} KWD</p>
                    </div>
                    <div class="d-flex justify-content-between d-total total-crt">
                        <strong>Total:</strong>
                        <strong>${data.total}KWD</strong>
                    </div>
                    <div class="proceed-checkout">
                        <a class="btn-site"><span>Place Order</span></a>
                    </div>
                ` ) 
            }else{
                $('#couponCalField').html(
                    ` <div class="d-flex justify-content-between d-total">
                        <p>Products Total</p>
                        <p>${data.products_total} KWD</p>
                    </div>
                    <div class="d-flex justify-content-between d-total">
                        <p>Discount</p>
                        <p> ${data.discount_amount}KWD</p>
                    </div>
                    <div class="d-flex justify-content-between d-total">
                        <p>Delivery Charges</p>
                        <p>{{ @$address->city->price }} KWD</p>
                    </div>
                    <div class="d-flex justify-content-between d-total total-crt">
                        <strong>Total:</strong>
                        <strong>${data.total_amount} KWD</strong>
                    </div>
                    
                     `
                    ) 
            } 

            }
        })
     }  

  couponCalculation();

              </script>
   
    
@yield('js')
@yield('script')

</body>
</html>
