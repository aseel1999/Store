@extends('layout.siteLayout')   
        @section('content')
        <section class="sign_page stage_padding">
		    <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="cont-login">
                            <div class="hd-sign">
                                <h6>@lang('website.login')</h6>
                            </div>
                            <form action="{{ route('users.login') }}"method="post" role="form"enctype="multipart/form-data" class="form-st form-login">
                                {{ csrf_field() }}
                            
                                <div class="form-group">
                                    <label>@lang('website.Email Address*')</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus >
                                </div>
                                <div class="form-group">
                                    <label>@lang('website.Password*')</label>
                                    <input type="password" class="form-control" name="password"  placeholder="Enter Here">
                                </div>
                                <div class="forgot-pass text-end">
                                  
                                </div>
                                <div class="form-group">
                                    <button class="btn-site" data-bs-toggle="modal" data-bs-target="#modalSendMs"><span>Login</span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
		    </div>
		</section>
   @endsection