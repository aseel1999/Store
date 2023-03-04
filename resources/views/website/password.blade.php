
@extends('layout.siteLayout')   
        @section('content')
        <section class="change_password_page stage_padding">
            <div class="container">
                <div class="row">
                    <aside class="col-lg-4">
                        <div class="aside-account wow fadeInUp">
                            <div class="txt-aside-account">
                                <h5>@lang('website.FullName')</h5>
                                <p>{{ @$user->user_name }}</p>
                                <p>{{ @$user->mobile }}</p>
                            </div>
                            <ul class="menu-profile">
                                <li><a href="my-order.html">My Orders</a></li>
                                <li><a href="{{(url(app()->getLocale().'/addresses'))}} ">Address</a></li>
                                <li><a href="edit-account.html">Edit Account</a></li>
                                <li class="active"><a href="change-password.html">Change Password</a></li>
                                <li><a href="index.html">Logout</a></li>
                            </ul>
                        </div>
                    </aside>
                    <div class="col-lg-8">
                        <div class="head-acco wow fadeInUp">
                            <h4>Change Password</h4>
                        </div>
                        <form class="form-edit form-st wow fadeInUp"action="{{url(app()->getLocale().'/updateUserPassword')}}"method="post" role="form" id="submitForm" >
                            @csrf
                            <div class="form-group">
                                <label>Old Password*</label>
                                <input type="password" name="password"
												class="form-control @error('password') is-invalid @enderror"  id="current_password" placeholder="Old Password" />

												@error('password') 
                                                  <span class="text-danger">{{ $message }}</span>
												@enderror
                            </div>
                            <div class="form-group">
                                <label>New Password*</label>
                                <input type="password" name="new_password"
                                class="form-control @error('new_password') is-invalid @enderror"  id="new_password" placeholder="New Password" />

                                @error('new_password') 
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password*</label>
                                <input type="password" name="new_password"
                                class="form-control "  id="new_password_confirmation" placeholder="Confirm New Password" />
                            </div> 
                            <div class="form-group">
                                <button  id="submitButton"class="btn-site"><span>Update</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
                   
   @endsection
   
   @section('js')



@endsection