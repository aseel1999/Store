@extends('layout.siteLayout')
@section('content')
<section class="sign_page stage_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
               <div class="cont-sign">
                   <div class="hd-sign">
                   <h6>@lang('website.NEW ACCOUNT')</h6>
                   </div>
                 <form action="{{route('users.store') }}" method="post" role="form" enctype="multipart/form-data" class="form-sign form-st">
                  {{ csrf_field() }}
                      <div class="form-group">
                       <label>@lang('website.Name*')</label>
                           <input type="text" class="form-control" name="user_name"placeholder="@lang('website.Name*')">
                        </div>
                       <div class="form-group">
                          <label>@lang('website.Email Address*')</label>
                            <input type="email" class="form-control" name="email"placeholder="@lang('website.Email Address*')">
                          </div>
                          <div class="form-group">
                            <label>@lang('website.Mobile Number*')</label>
                             <div class="d-flex">
                                 <input type="number" class="form-control" name="mobile"placeholder="@lang('website.Phone Number*')">
                            </div>
                           </div>
                            <div class="form-group" >
                               <label>@lang('website.Password*')</label>
                                  <input type="password" class="form-control"  name="password"placeholder="@lang('website.Phone Number*')">
                              </div>
                              <div class="form-group">
                                <label>@lang('website.Confirm Password*')</label>
                               <input type="password" class="form-control" name="confirm_password" placeholder="@lang('website.Confirm Password*')">
                               </div>
                                 <div class="form-group">
                                     <button class="btn-site" data-bs-toggle="modal" data-bs-target="#modalSuccRegistered"><span>Signup</span></button>
                                </div>
        </form>
    </div>
</div>
        </div>
    </div>
</section>
@endsection