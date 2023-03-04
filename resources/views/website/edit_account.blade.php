@extends('layout.siteLayout')
@section('content')
<section class="edit_account_page stage_padding">
    <div class="container">
        <div class="row">
            <aside class="col-lg-4">
                <div class="aside-account wow fadeInUp">
                    <div class="txt-aside-account">
                        <h5>Full Name</h5>
                        <p>{{ $user->email }}</p>
                        <p>{{ $user->mobile }}</p>
                    </div>
                    <ul class="menu-profile">
                        <li><a href="my-order.html">My Orders</a></li>
                        <li><a href="my-address.html">Address</a></li>
                        <li class="active"><a href="edit-account.html">Edit Account</a></li>
                        <li><a href="change-password.html">Change Password</a></li>
                        <li><a href="index.html">Logout</a></li>
                    </ul>
                </div>
            </aside>
        <div class="col-lg-8">
          <div class="head-acco wow fadeInUp">
             <h4>Edit Account</h4>
           </div>
              <form id="submitForm" class="form-edit form-st wow fadeInUp"method="post" role="form" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PATCH')}}
        <div class="form-group">
            <label>Full Name*</label>
            <input type="text" class="form-control" name="user_name" value="{{old('user_name',$user->user_name) }}"required/>
        </div>
        <div class="form-group">
            <label>Email Address*</label>
            <input type="email" class="form-control" name="email"value="{{old('email',$user->email) }}"required/>
        </div>
        <div class="form-group">
            <label>Mobail Number*</label>
            <div class="d-flex ds-mobail">
                <input
                onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"

                type="text" class="form-control form-control-solid" name="mobile"
                value="{{old('mobile',$user->mobile) }}" required/>
            </div>
        </div>
        <div class="form-group">
            <button id="submitButton" class="btn-site" ><span>Update</span></button>
        </div>
      </form>
    </div>
         </div>
       </div>
</section>
        
@endsection
@section('js')
<script>
    $('#submitButton').click(function(e){
    e.preventDefault(); 
    $.ajax({
        url: '{{(url(app()->getLocale().'/users/account/edit/'.$user->id))}}',
        data: $("#submitForm").serialize(),
        type: "POST",
        headers: {
            'X-CSRF-Token': '{{ csrf_token() }}',
        },
        success: function(data){
            alert("okay");

        }, 
        error: function(){
              alert("failure From php side!!! ");
         }

        }); 

        });
    
</script>


@endsection
        
    <!--main-wrapper-->
    
