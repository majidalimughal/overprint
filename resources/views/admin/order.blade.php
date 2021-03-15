@extends('layouts.admin')
@section('content')
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3><strong>Orders</strong></h3>
        </div>
{{--        <div class="col-md-7 text-right">--}}
{{--            <a class="btn btn-warning" href="{{route('orders.sync')}}"> Sync New Orders </a>--}}
{{--        </div>--}}
    </div>


    <div class="row">
        <div class="col-md-12 pr-0  ">
            <div class="row p-0 m-2  "  >
                <div class=" col-md-6 col-sm-6 bg-white">
                    <div class="row">
                        <div class="col-md-1 col-sm-3 pr-1 pt-2">
                            <i class="ti-search "></i>
                        </div>
                        <div class="col-md-11 col-sm-9 pl-0">
                            <input type="text" class="border-0 form-control filter-search" data-route="{{route('admin.orders.filter')}}" autocomplete="false"  name="search" placeholder="Filter Orders By ID, Name, Email" required>

                        </div>
                    </div>
                </div>

            </div>

        </div>



    </div>

    <div class="orders">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table v-middle" id="order_table">
                            <thead>
                            <tr class="bg-light ">
                                <th >
                                    <div class="checkbox">
                                        <input type="checkbox" class="" id="check-all">
                                        <label for="check-all" class="check-all"></label>
                                    </div>
                                </th>
                                <th class="border-top-0">ID</th>
                                <th class="border-top-0" >Date</th>
                                <th class="border-top-0">Email</th>
                                <th class="border-top-0">Send Email</th>
                                <th class="border-top-0">Last Email</th>
                                <th class="border-top-0">Last Updated Designer</th>
                                <th class="border-top-0">Design Status</th>
                                <th class="border-top-0">Details</th>
                                <th class="border-top-0">Order Status</th>
                            </tr>
                            </thead>
                            <tbody id="myTable">
                            @foreach($orders as $index => $order)
                                <tr>
                                    <td>
                                        <div class="checkbox" >
                                            <input type="checkbox" data-order_id="{{$order->id}}" class="checkSingle" id="check-{{$index}}" >
                                            <label for="check-{{$index}}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{route('admin.order.detail', $order->id)}}">{{ $order->name }}</a>
                                    </td>
                                    <td style="white-space: nowrap">{{date_create($order->created_at)->format('Y-m-d')}}</td>
                                    <td align="center">{{ $order->email }}</td>
                                    <td align="center">
                                        <div class="button-group">
                                            <a  data-id="{{$order->id}}" data-route="" class="send-email btn waves-effect waves-light btn-rounded btn-xs btn-info text-white">
                                                Send
                                            </a>
                                        </div>
                                    </td>
                                    <td style="white-space: nowrap">
                                        {{$order->last_email_at}}
                                    </td>
                                    <td align="center">
                                        @if($order->has_designer != null)
                                            <span class="badge badge-pill designer" style="color: {{$order->has_designer->color}}; background: {{$order->has_designer->background_color}}">{{$order->has_designer->name}} </span>
                                        @endif
                                    </td>
                                    <td align="center" class="design-status">
                                        @if(count($order->has_lineItems) >  0)
                                            @foreach($order->has_lineItems as $index => $product)
                                                @if($product->has_design != null)
                                                    @if($product->has_design->status_id == 3)
                                                        <div class="setting_div">
                                                            <span class="mdi mdi-settings text-white display-6"></span>
                                                        </div>
                                                        <h6 class="settings_iicon" data-text="{{$product->has_design->status}}"><b>{{$product->has_design->status}}</b></h6>
                                                    @elseif($product->has_design->status_id == 6)
                                                        <div class="approved_div">
                                                            <span class="mdi mdi-check-circle-outline check_mark"></span>
                                                        </div>
                                                        <h6 class="approved" data-text="{{$product->has_design->status}}"><b>{{$product->has_design->status}}</b></h6>
                                                        <span style="white-space: nowrap" class="approved"><b>{{date_create($product->approved_date)->format('Y-m-d')}}</b></span>

                                                    @elseif($product->has_design->status_id == 8)
                                                        <div class="cir">
                                                            <span class="rec"></span>
                                                        </div>
                                                        <h6 class="not_completed" data-text="{{$product->has_design->status}}"><b>{{$product->has_design->status}}</b></h6>
                                                    @elseif($product->has_design->status_id == 7)
                                                        <div class="update_div"  title="{{$product->has_design->status_text}}">
                                                            <span class="update_icon">!</span>
                                                        </div>
                                                        <h6 class="updating"   title="{{$product->has_design->status_text}}" data-text="{{$product->has_design->status}}"><b>{{$product->has_design->status}}</b></h6>
                                                    @endif
                                                @endif

                                            @endforeach
                                        @else
                                            <div class="cir">
                                                <span class="rec"></span>
                                            </div>
                                            <h6 class="not_completed" data-text="{{$product->has_design->status}}"><b>No Design</b></h6>
                                        @endif

                                    </td>

                                    <td align="center">

                                    </td>

                                    <td class="order-status-td" style="background: #0066CC;color:#ffff">

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center" style="display: flex;justify-content: center;align-items: center;margin: 10px;">
                        {{ $orders->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
