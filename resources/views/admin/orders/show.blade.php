@extends('layout.adminLayout')
@section('title') {{ucwords(__('cp.show_order'))}}
@endsection
@section('css')

@endsection
@section('content')

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
            <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline mr-5">
                        <h3>{{__('cp.show_order')}}</h3>
                    </div>
                </div>
                <!--end::Info-->
                <!--begin::Toolbar-->
                <div class="d-flex align-items-center">
                    <a href="{{url(getLocal().'/admin/orders')}}"
                       class="btn btn-secondary  mr-2">{{__('cp.cancel')}}</a>
                    <button id="submitButton" class="btn btn-success ">{{__('cp.save')}}</button>
                </div>
                <!--end::Toolbar-->
            </div>
        </div>
        <!--end::Subheader-->
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact">
                    <form method="post" action="{{url(app()->getLocale().'/admin/orders/'.$item->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}
                        <div class="card-header">
                            <h3 class="card-title">{{__('cp.main_info')}}</h3>
                        </div>
                        <div class="row col-sm-12">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('cp.status')}}</label>
                                            <select class="form-control form-control-solid"
                                                    name="status" @if($item->status == '3' || $item->status == '4') disabled @endif required>
                                                <option
                                                    value="1" {{$item->status == '1'?'selected':''}}>
                                                    {{__('cp.in_process')}}
                                                </option>
                                                <option
                                                    value="2" {{$item->status == '2'?'selected':''}}>
                                                    {{__('cp.on_the_way')}}
                                                </option>
                                                <option
                                                    value="3" {{$item->status == '3'?'selected':''}}>
                                                    {{__('cp.completed')}}
                                                </option>
                                                <option
                                                    value="4" {{$item->status == '5'?'selected':''}}>
                                                    {{__('cp.canceled')}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('cp.payment_method')}}</label>
                                            <input type="text" class="form-control form-control-solid"
                                                   name="payment_method"
                                                   value="{{@$item->payment_method=='1'?__('cp.online'):__('cp.cache_on_pickup')}}" disabled/>
                                        </div>
                                    </div>
                                  
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('cp.customer_name')}}</label>
                                            <input type="text" class="form-control form-control-solid"
                                                   name="customer_name"
                                                   value="{{old('customer_name',@$item->user->user_name)}}" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('cp.customer_email')}}</label>
                                            <input type="text" class="form-control form-control-solid"
                                                   name="customer_email"
                                                   value="{{old('customer_email',@$item->user->email)}}" disabled/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('cp.customer_mobile')}}</label>
                                            <input type="text" class="form-control form-control-solid"
                                            value="{{old('customer_mobile',@$item->user->mobile)}}" disabled/>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('cp.order_date')}}</label>
                                            <input type="text" class="form-control form-control-solid"
                                                   value="{{@$item->order_date}}"
                                                   disabled/>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="card-header col-md-12">
                                <h3 class="card-title">{{__('cp.price')}}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('cp.total')}}</label>
                                            <input type="text" class="form-control form-control-solid"
                                                   value="{{@$item->sub_total}}" disabled/>
                                        </div>
                                    </div>
                                    @if($item->discount > 0)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__('cp.total_after_discount')}}</label>
                                                <input type="text" class="form-control form-control-solid"
                                                       value="{{@$item->total}}" disabled/>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>


                        @if($item->discount > 0)
                            <div class="card-header col-md-12">
                                <h3 class="card-title">{{__('cp.discount')}}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('cp.promo_code_name')}}</label>
                                            <input type="text" class="form-control form-control-solid"
                                                   value="{{@$item->promo_code_name}}" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('cp.promo_code_amount')}}</label>
                                            <input type="text" class="form-control form-control-solid"
                                                   value="{{@$item->promo_code_amount}}  {{$item->promo_code_type==0 ? '%' : ''}}" disabled/>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('cp.total_discount')}}</label>
                                            <input type="text" class="form-control form-control-solid"
                                                   value="{{@$item->discount}}" disabled/>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        @endif

                        @if($item->payment_method == 1 && $item->status =='4')
                                <div class="card-header col-md-12">
                                    <h3 class="card-title">{{__('cp.refund')}}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>{{__('cp.status')}}</label>
                                                <input type="text" class="form-control form-control-solid"
                                                       value="{{@$item->payment_status==1 ? __('cp.refund_success'): __('cp.refund_error')}}" disabled/>
                                            </div>
                                        </div>

                                       
                                    </div>

                                    
                                </div>
                            @endif

                        @foreach($item->products as $one)
                                <div class="card-header col-md-12">
                                    <h3 class="card-title">{{@$one->product->name}}</h3>
                                </div>
                                <div class="card-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('cp.quantity')}}</label>
                                            <input type="text" class="form-control form-control-solid"
                                                   value="{{@$one->quantity}}" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('cp.price')}}</label>
                                            <input type="text" class="form-control form-control-solid"
                                                   value="{{@$one->price}}" disabled/>
                                        </div>
                                    </div>
                                </div>


                                    

                                </div>


                            @endforeach


                        </div>

                        <button type="submit" id="submitForm" style="display:none"></button>
                    </form>

                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>



@endsection
@section('js')

@endsection

@section('script')

    <script>
        $(document).on('click', '#submitButton', function () {
            // $('#submitButton').addClass('spinner spinner-white spinner-left');
            $('#submitForm').click();
        });
    </script>
@endsection
