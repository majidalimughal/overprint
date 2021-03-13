@extends('layouts.admin')
@section('content')
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3><strong>New Orders</strong></h3>
        </div>
        {{--                <div class="col-md-7 text-right">--}}
        {{--                    <a class="btn btn-warning" href="{{route('orders.sync')}}"> Sync All New Orders </a>--}}
        {{--                </div>--}}
    </div>
    <div class="row">
        <div class="col-md-2 pl-0">
            <div class="flexing">
                <div class=" dropdowns actionbox">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="pr-5">Actions</span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" >Sync</a>
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
                                <th class="border-top-0" style="text-align: left !important;">ID</th>
                                <th class="border-top-0" style="text-align: left !important;">Date</th>
                                <th class="border-top-0" style="text-align: left !important;">Email</th>
                                <th class="border-top-0" style="text-align: left !important;">Assigned to Designer</th>
                                <th class="border-top-0">Action</th>
                                <th class="border-top-0">Order Status</th>
                            </tr>

                            </thead>
                            <tbody id="myTable">
                            @foreach($orders as $order)
                                @if($order->sync == 'no')
                                <tr>
                                    <td>
                                        @if($order->sync == 'no')
                                            @if($order->designer_id != null)
                                            <a href="{{route('orders.sync.order.detail',[
                                                'id' =>$order->id,
                                                'designer_id'=>$order->designer_id
                                                ])}}">
                                                {{ $order->name }}
                                            </a>
                                                @else
                                                {{ $order->name }}
                                            @endif
                                        @else
                                            {{ $order->name }}
                                        @endif

                                    </td>
                                    <td> {{ date_create($order->created_at)->format('Y-m-d H:i a') }}</td>
                                    <td >{{ $order->email }}</td>
                                    <td>
                                        <span class="badge badge-pill designer" style="color: {{$order->designer_color}}; background: {{$order->designer_background}}">{{$order->designer}} </span>
                                    </td>
                                    <td align="center">
                                        @if($order->sync == 'no')
                                            @if($order->designer_id != null)
                                                <button onclick="window.location.href='{{route('orders.sync.order',[
                                                'id' =>$order->id,
                                                'designer_id'=>$order->designer_id
                                                ])}}'" class="btn btn-sm btn-warning "><i class="text-white font-16 mdi mdi-sync"></i></button>
                                            @endif
                                        @endif
                                    </td>
                                    @if($order->sync == 'yes')
                                        <td align="center" class="order-status-td" style="background: green;color:#ffff">
                                            <div class="dropdown">
                                                <span style="cursor: pointer;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Synchronised </span>
                                            </div>
                                        </td>
                                    @else
                                        <td align="center" class="order-status-td not-synced" style="background: red;color:#ffff">
                                            <div class="dropdown">
                                                <span @if($order->designer_id != null) onclick="window.location.href='{{route('orders.sync.order',[
                                                'id' =>$order->id,
                                                'designer_id'=>$order->designer_id
                                                ])}}'" @endif style="cursor: pointer;float: right" class="pr-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> @if($order->designer_id != null) Need Synchronisation @else Cant Sync @endif </span>
                                            </div>

                                        </td>
                                    @endif
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center" style="display: flex;justify-content: center;align-items: center;margin: 10px;">
                        <nav aria-label="...">
                            <ul class="pagination">
                                <li class="page-item @if($previous == null) disabled @endif">
                                    <a class="page-link" href="{{route('orders.new')}}?page={{$previous}}" tabindex="-1">Previous</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="{{route('orders.new')}}?page={{$next}}">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
