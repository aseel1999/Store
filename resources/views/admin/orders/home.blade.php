@extends('layout.adminLayout')
@section('title') {{ucwords(__('cp.orders'))}}
@endsection
@section('css')

    <style>

        a:link {
            text-decoration: none;
        }
    </style>

@endsection
@section('content')



    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
            <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline mr-5">
                        <h3>{{ucwords(__('cp.orders'))}}</h3>
                    </div>
                </div>
                <!--end::Info-->
                <!--begin::Toolbar-->


                <div>

                    <div class="btn-group mb-2 m-md-3 mt-md-0 ml-2 ">
                        <a  class="btn btn-secondary" href="{{url('admin/pdfOrders')}}">
                            <i class="icon-xl la la-file-pdf"></i>
                            <span>PDF</span>
                        </a>
                        <a  class="btn btn-secondary btn_export" href="{{url('admin/export/excel/orders')}}">
                            <i class="icon-xl la la-file-excel"></i>
                            <span>{{__('cp.excel')}}</span>
                        </a>

                        <button type="button" class="btn btn-secondary" href="#deleteAll" role="button"
                                data-toggle="modal">
                            <i class="flaticon-delete"></i>
                            <span>{{__('cp.delete')}}</span>
                        </button>
                    </div>
                    <a href="{{url(getLocal().'/admin/orders/create')}}" class="btn btn-secondary  mr-2 btn-success">
                        <i class="icon-xl la la-plus"></i>
                        <span>{{__('cp.add')}}</span>
                    </a>
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
                <div class="gutter-b example example-compact">

                    <div class="contentTabel">
                        <button type="button" class="btn btn-secondar btn--filter mr-2"><i
                                class="icon-xl la la-sliders-h"></i>{{__('cp.filter')}}</button>
                        <div class="container box-filter-collapse">
                            <div class="card">
                               
                            </div>
                        </div>
                        <div
                            class="card-header d-flex flex-column flex-sm-row align-items-sm-start justify-content-sm-between">
                            <div>


                            </div>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover tableWithSearch" id="tableWithSearch">
                                <thead>
                                <tr>
                                    <th class="wd-1p no-sort">
                                        <div class="checkbox-inline">
                                            <label class="checkbox">
                                                <input type="checkbox" name="checkAll"/>
                                                <span></span></label>
                                        </div>
                                    </th>

                                <!--{{--                                                        <th class="wd-5p"> {{ucwords(__('cp.image'))}}</th>--}}-->
                                    <th class="wd-25p">ID</th>
                                    <th class="wd-25p"> {{ucwords(__('cp.customer_name'))}}</th>
                                    <th class="wd-25p"> {{ucwords(__('cp.customer_mobile'))}}</th>
                                    <th class="wd-25p"> {{ucwords(__('cp.customer_email'))}}</th>
                                    <th class="wd-25p"> {{ucwords(__('cp.total_price'))}}</th>
                                    <th class="wd-25p"> {{ucwords(__('cp.payment_method'))}}</th>
                                    <th class="wd-10p"> {{ucwords(__('cp.status'))}}</th>
                                    <th class="wd-10p"> {{ucwords(__('cp.created'))}}</th>
                                    <th class="wd-15p"> {{ucwords(__('cp.action'))}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($items as $one)
                                    <tr class="odd gradeX" id="tr-{{$one->id}}">
                                        <td class="v-align-middle wd-5p">
                                            <div class="checkbox-inline">
                                                <label class="checkbox">
                                                    <input type="checkbox" value="{{$one->id}}" class="checkboxes"
                                                           name="chkBox"/>
                                                    <span></span></label>
                                            </div>
                                        </td>

                                        <td class="v-align-middle wd-25p">{{@$one->id}}</td>
                                        <td class="v-align-middle wd-25p">{{@$one->user->user_name}}</td>
                                        <td class="v-align-middle wd-25p">{{$one->user->mobile}}</td>
                                        <td class="v-align-middle wd-25p">{{$one->user->email}}</td>
                                        <td class="v-align-middle wd-25p">{{$one->total}}$</td>
                                        <td data-field="Status" aria-label="6" class="datatable-cell"><span
                                                style="width: 108px;"><span
                                                    class="label font-weight-bold label-lg  label-light-{{$one->payment_method==1?'success':'warning'}} label-inline">{{$one->payment_method==1?__('cp.online'):__('cp.cache_on_pickup')}}</span></span>
                                        </td>


                                        <td class="v-align-middle wd-10p"> <span id="label-{{$one->id}}"
                                                                                 class="badge badge-pill badge-{{$one->status_badge}}">
                                                {{$one->status_text}}</span>
                                        </td>

                                        <td class="v-align-middle wd-10p">{{$one->created_at->format('Y-m-d - h:i A')}}</td>

                                        <td class="v-align-middle wd-15p optionAddHours">


                                            <a href="{{url(getLocal().'/admin/orders/'.$one->id.'/edit')}}"
                                               class="btn btn-sm btn-bg-clean btn-icon" title="{{__('cp.show')}}">
                                                <i class="la la-eye"></i>

                                            </a>
                                            

                                            @if($one->status !=3 && $one->status!=4)
                                            <a href="#myModal{{$one->id}}" role="button" title="{{__('cp.change_status')}}"
                                               data-toggle="modal" class="btn btn-sm btn-clean btn-icon"><i
                                                    class="las la-exchange-alt"></i></a>
                                            @endif                         

                                        </td>
                                    </tr>
                                    <div id="myModal{{$one->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4 class="modal-title">{{__('cp.change_status')}}</h4>
                                                </div>
                                                <div class="modal-body">
                                                        <ul class="navi flex-column navi-hover py-2">
                                                            <li class="navi-item"><a href="{{route('admin.changeOrderStatus',[$one->id , '1'])}}" class="navi-link"> <span
                                                                        class="navi-icon"><i class="la la-dot-circle-o"></i></span>
                                                                    <span class="navi-text">@lang('cp.in_process')</span> </a></li>
                                                            <li class="navi-item"><a href="{{route('admin.changeOrderStatus',[$one->id , '2'])}}" class="navi-link"> <span
                                                                        class="navi-icon"><i class="la la-dot-circle-o"></i></span>
                                                                    <span class="navi-text">@lang('cp.on_the_way')</span> </a></li>
                                                            <li class="navi-item"><a href="{{route('admin.changeOrderStatus',[$one->id , '3'])}}" class="navi-link"> <span
                                                                        class="navi-icon"><i class="la la-dot-circle-o"></i></span>
                                                                    <span class="navi-text">@lang('cp.completed')</span> </a></li>


                                                            <li class="navi-item"><a href="{{route('admin.changeOrderStatus',[$one->id , '4'])}}" class="navi-link"> <span
                                                                        class="navi-icon"><i class="la la-dot-circle-o"></i></span>
                                                                    <span class="navi-text">@lang('cp.canceled')</span> </a></li>
                                                            
                                                                        class="navi-icon"><i
                                                                            class="la la-dot-circle-o"></i></span> <span
                                                                        class="navi-text">@lang('cp.canceled')</span> </a></li>
                                                        </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn default" data-dismiss="modal" aria-hidden="true">{{__('cp.cancel')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                @empty
                                @endforelse

                                </tbody>
                            </table>
                            {{$items->appends($_GET)->links("pagination::bootstrap-4") }}
                        </div>
                    </div>
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
@endsection
