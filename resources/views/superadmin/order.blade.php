@extends('layouts.main')
@section('content')
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3><strong>Orders</strong></h3>
        </div>
        <div class="col-md-7 text-right">
            <a class="btn btn-warning" href="{{route('sync.Orders')}}"> Sync New Orders </a>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 pr-0  ">
            <div class="row p-0 m-2  ">
                <div class=" col-md-6 col-sm-6 bg-white">
                    <div class="row">
                        <div class="col-md-1 col-sm-3 pr-1 pt-2">
                            <i class="ti-search "></i>
                        </div>
                        <div class="col-md-11 col-sm-9 pl-0">
                            <input type="text" class="border-0 form-control filter-search"
                                   data-route="{{route('admin.orders.filter')}}" autocomplete="false" name="search"
                                   placeholder="Filter Orders By ID, Name, Email" required>

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
                                <th>
                                    <div class="checkbox">
                                        <input type="checkbox" class="" id="check-all">
                                        <label for="check-all" class="check-all"></label>
                                    </div>
                                </th>
                                <th class="border-top-0">ID</th>
                                <th class="border-top-0">Shop</th>
                                <th class="border-top-0">Date</th>
                                <th class="border-top-0">Email</th>
                                <th class="border-top-0">Products</th>
                                <th class="border-top-0">Fullfillment Status</th>
                                <th class="border-top-0">Financial Status</th>
                                <th class="border-top-0">Price</th>
                                @isset($admin)
                                    <th class="border-top-0">Fulfill Order</th>
                                @endisset
                            </tr>
                            </thead>
                            <tbody id="myTable">
                            @foreach($orders as $index => $order)
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <input type="checkbox" data-order_id="{{$order->id}}" class="checkSingle"
                                                   id="check-{{$index}}">
                                            <label for="check-{{$index}}"></label>
                                        </div>
                                    </td>
                                    <td>@if(isset($order->has_shop->name)) {{$order->has_shop->name}} @endif</td>
                                    <td>
                                        <a href="{{route('admin.orders.detail', $order->id)}}">{{ $order->name }}</a>
                                    </td>
                                    <td style="white-space: nowrap">{{date_create($order->created_at)->format('Y-m-d')}}</td>
                                    <td align="center">{{ $order->email }}</td>
                                    <td style="white-space: nowrap">
                                        @foreach($order->has_lineItems as $item)
                                            <span class="badge badge-warning d-block my-1">{{$item->title}}</span>
                                        @endforeach
                                    </td>
                                    <td style="white-space: nowrap;text-align: center">
                                        @if($order->fulfillment_status==null)
                                            <span class="badge badge-warning">Unfulfilled</span>
                                        @elseif($order->fulfillment_status=='partial')
                                            <span class="badge badge-primary">Partially Fulfilled</span>
                                        @elseif($order->fulfillment_status=='fulfilled')
                                            <span class="badge badge-success">Fulfilled</span>
                                        @else
                                            <span class="badge badge-danger">Restocked</span>
                                        @endif
                                    </td>
                                    <td style="white-space: nowrap;text-align: center">
                                        @if($order->financial_status=="unpaid")
                                            <span class="badge badge-warning">Unpaid</span>
                                        @elseif($order->financial_status=='authorized')
                                            <span class="badge badge-primary">Authorized</span>
                                        @elseif($order->financial_status=='paid')
                                            <span class="badge badge-success">Paid</span>
                                        @elseif($order->financial_status=='partially_paid')
                                            <span class="badge badge-success">Partially Paid</span>
                                        @else
                                            <span
                                                class="badge badge-danger">{{ucwords(str_replace('_',' ',$order->financial_status))}}</span>
                                        @endif
                                    </td>
                                    <td align="center" class="design-status">
                                        ${{number_format($order->price,2)}}

                                    </td>

                                    @isset($admin)
                                        <td align="center">
                                            @if($order->cancelled_at===null && !in_array($order->fulfillment_status,['fulfilled','partial']))
                                                <a href="{{ route('admin.order.fulfillment', $order->id) }}" class="btn btn-primary mt-1" >FulFill Order</a>
                                            @endif
                                        </td>
                                    @endisset
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center"
                         style="display: flex;justify-content: center;align-items: center;margin: 10px;">
                        {{ $orders->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
