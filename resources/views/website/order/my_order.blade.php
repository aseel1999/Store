@extends('layout.siteLayout')
@section('content')
<section class="order_page stage_padding">
    <div class="container">
        <div class="row">
            <aside class="col-lg-4">
                <div class="aside-account wow fadeInUp">
                    <div class="txt-aside-account">
                        <h5>Full Name</h5>
                        <p>ala@gmail.com</p>
                        <p>0011223344</p>
                    </div>
                    <ul class="menu-profile">
                        <li class="active"><a href="my-order.html">My Orders</a></li>
                        <li><a href="my-address.html">Address</a></li>
                        <li><a href="edit-account.html">Edit Account</a></li>
                        <li><a href="change-password.html">Change Password</a></li>
                        <li><a href="index.html">Logout</a></li>
                    </ul>
                </div>
            </aside>
            <div class="col-lg-8">
                <div class="head-acco wow fadeInUp">
                    <h4>My Orders</h4>
                </div>
                <div class="table-responsive-lg table-order wow fadeInUp">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Order ID</th>
                                <th>Items</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->date }}</td>
                                <td>{{ $order->id }}</td>
                                <td class="text-center">{{ $order->items }}</td>
                                <td>{{ $order->amount }}</td>
                                <td>
                                    <div class="status-order">
                                        <p>{{ $order->status }}</p>
                                        <a href="{{ route('orderdetails',$order->id) }}" class="btn-site"><span>View</span></a>
                                    </div>
                                </td>
                            </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</section>

@endsection