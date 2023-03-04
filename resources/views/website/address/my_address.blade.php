@extends('layout.siteLayout')
@section('content')
<section class="address_page stage_padding">
    <div class="container">
        <div class="row">
            <aside class="col-lg-4">
                <div class="aside-account wow fadeInUp">
                    <div class="txt-aside-account">
                        <h5>Full Name</h5>
                        <p></p>
                        <p></p>
                    </div>
                    <ul class="menu-profile">
                        <li><a href="my-order.html">My Orders</a></li>
                        <li class="active"><a href="my-address.html">Address</a></li>
                        <li><a href="edit-account.html">Edit Account</a></li>
                        <li><a href="change-password.html">Change Password</a></li>
                        <li><a href="index.html">Logout</a></li>
                    </ul>
                </div>
            </aside>
            <div class="col-lg-8">
                <div class="head-acco wow fadeInUp">
                    <h4>My Address</h4>
                    <a class="btn-site btnSt" data-bs-toggle="modal" data-bs-target="#modalAdderss"><span>New Address</span></a>
                </div>
                <div class="cont-address wow fadeInUp">
                    <div class="row">
                       
                        <div class="col-lg-6">
                             @foreach($addresses as $address)
                            <div class="item-addres wow fadeInUp">
                                <h6>{{ $address->address_name }}</h6>
                                <p>{{ $address->country->country_name }}</p>
                                <p>{{ $address->city->city_name }}</p>
                                <p>{{ $address->building }}</p>
                                <ul>
                                    <li><a class="btnSt" data-toggle="modal" data-target="#modalDelete{{ $address->id }}"><i class="fa-solid fa-trash-can"></i></a></li>
                                    <li><a class="btnSt" data-toggle="modal" data-target="#modalEdit"><i class="fa-solid fa-pen"></i></a></li>
                                </ul>
                            </div>
                           
                            
                            @endforeach

                        </div>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    

    
    
@endsection    
