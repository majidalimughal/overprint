@extends('layouts.admin')
@section('content')


    <style>
    a {
        color: #6C757D;
    }
    a:hover {
        text-decoration: none;
    }
    @media only screen and (max-width: 767px) {
        .offset-2 {
            margin-left: 0px !important;
        }
        .row {
            display: block;
            margin: 0 auto;
        }
    }
    .center {
        display: block;
        margin: 0 auto;
        text-align: center;
    }
    t
    .card {
        /*border: 3px solid #7df27d;*/
    }
    tr th{
        text-align: left!important;
    }
    /*.badge*/
    /*{*/
    /*    font-size: 15px!important;*/
    /*    padding: 10px 20px !important;*/
    /*    float:right !important;*/
    /*    position: absolute;*/
    /*    right: 10px;*/
    /*    top: 18px;*/
    /*}*/
</style>

    <div class="row pl-5">
        <div class="col-sm-12 mt-3">
            <h3><b>Order {{ $order->name }}</b></h3>
        </div>

    </div>
    <div class="row pt-4">
        <div class="col-sm-6  col-md-5">
            <div class="card">
                <div class="card-block">
                    <div class="flexing">
                        <div class="col-md-11">
                            <h6><b>Notes</b></h6>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit_notes">Edit
                            </button>
                            <!-- Modal -->
                            <form action="{{route('order.notes',$order->id)}}" method="POST">
                                @csrf
                                <div class="modal fade" id="edit_notes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Notes</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" name="notes" value="{{$order->notes}}" class="form-control"/>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button  class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="flexing ">
                        <div class="col-md-12">@if($order->notes)
                            <p>{{ $order->notes }}</p>
                        @else
                            <p>No notes from customer</p>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="card p-3">
                <div class="card-header">
                    <h5>Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive overflow-none">
                        <table class="table table-striped text-left">
                            <thead>
                            <tr>
                                <th>Thumbnail</th>
                                <th>Title</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->has_lineItems as $line)
                                <tr>
                                    <td><img width="200px" height="100%" class="img img-thumbnail my-2" @if($line->image!==null) src="{{$line->image}}" @else src="{{asset('img/noimg.svg')}}" @endif/></td>
                                    <td>{{ucwords($line->title)}}</td>
                                    <td>{{$line->quantity}}</td>
                                    <td>{{$line->currency.$line->price}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 pl-3 pt-2">
            <div class="card p-3">
                <div class="card-body">
                    <h4>Contact Information</h4>
                    <p><b>Name</b>:
                        @if(isset( $order->shipping_address['first_name'])){{$order->shipping_address['first_name']}}@endif
                        @if(isset( $order->shipping_address['last_name'])){{$order->shipping_address['last_name']}}@endif
                    </p>
                    <p><b>Email</b>: {{$order->email}}</p>

                    <h4>Order Detail</h4>
                    @if($order->total_line_item_price!==null)<p><b>Sub Total</b>:
                        ${{number_format(floatval($order->total_line_item_price),2)}}
                    </p>@endif
                    @if($order->discount_amount!==null)<p><b>Discount</b>:
                        ${{number_format(floatval($order->discount_amount),2)}}</p>@endif
                    @if($order->price!==null)<p><b>Total</b>: ${{number_format(floatval($order->price),2)}}
                    </p>@endif
                    @if($order->payment!==null && isset($order->payment['payment_method']))<p><b>Payment
                            Method</b>: {{ucwords(str_replace('_',' ',$order->payment['payment_method']))}}
                    </p> @endif
                    @if($order->fulfillment_status==null)
                        <span class="badge badge-warning">Unfulfilled</span>
                    @elseif($order->fulfillment_status=='partial')
                        <span class="badge badge-primary">Partially Fulfilled</span>
                    @elseif($order->fulfillment_status=='fulfilled')
                        <span class="badge badge-success">Fulfilled</span>
                    @else
                        <span class="badge badge-danger">Restocked</span>
                    @endif
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
                </div>
            </div>
        </div>

        @if($order->fulfillment_status == 'fulfilled')
        <div class="col-md-12 my-2">
            <div class="card p-3">
                <div class="card-body">
                    <h4>Fulfillments</h4>
                    @foreach($order->fulfillments as $fulfillment)
                        <div >
                            <div class="d-flex justify-content-between">
                                <h3 class="mb-0">
                                    {{$fulfillment->name}}
                                </h3>

                                <span class="badge badge-primary align-self-basline" style="font-size: medium"> {{$fulfillment->status}}</span>
                            </div>
                            <div class="block-content">
                                @if($fulfillment->tracking_number != null)
                                    <p style="font-size: 12px">
                                        Tracking Number : {{$fulfillment->tracking_number}} <br>
                                        Tracking Url : {{$fulfillment->tracking_url}} <br>
                                        Tracking Notes : {{$fulfillment->tracking_notes}} <br>

                                    </p>
                                @endif
                                <table class="table table-borderless table-striped table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Thumbnail</th>
                                            <th>Title</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($fulfillment->line_items as $item)
                                        <tr>
                                        
                                            <td><img width="200px" height="100%" class="img img-thumbnail my-2" @if($item->line_item->image!==null) src="{{$item->line_item->image}}" @else src="{{asset('img/noimg.svg')}}" @endif/></td>
                                            
                                            <td style="width: 60%">
                                                @if($item->line_item != null)
                                                    {{$item->line_item->title}}
                                                @endif
                                            </td>
                                            <td> 
                                                {{number_format($item->line_item->price,2)}}  X {{$item->fulfilled_quantity}}  USD
                                            </td>

                                            <td> 
                                                {{number_format($item->line_item->price,2)}}
                                            </td>

                                        </tr>
                                    @endforeach
                                    @if($fulfillment->tracking_number == null)
                                        <tr>
                                            <td colspan="12" class="text-right">
                                                <button class="btn btn-sm btn-danger" onclick="window.location.href='{{route('admin.order.fulfillment.cancel',['id'=>$order->id,'fulfillment_id'=>$fulfillment->id])}}'"> Cancel Fulfillment </button>
                                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_tracking_modal{{$fulfillment->id}}"> Add tracking </button>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div class="modal fade" id="add_tracking_modal{{$fulfillment->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
                                <div class="modal-content p-4">
                                    <div class="block block-themed block-transparent mb-0">
                                        <div class="block-header bg-primary-dark">
                                            <h3 class="block-title">Add Tracking to Fulfillment</h3>
                                        </div>
                                        <form action="{{route('admin.order.fulfillment.tracking',$order->id)}}" method="post">
                                            @csrf
                                            <div class="p-3">
                                                <input type="hidden" name="fulfillment[]" value="{{$fulfillment->id}}">
                                                <div class="block">
                                                    <div class="block-header block-header-default">
                                                        <h3 class="block-title">
                                                            {{$fulfillment->name}}
                                                        </h3>
                                                    </div>
                                                    <div class="block-content">
                                                        <table class="table table-borderless  table-vcenter">
                                                            <thead>

                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Tracking Number <span style="color: red">*</span></td>
                                                                    <td>
                                                                        <input type="text" required name="tracking_number[]" class="form-control" placeholder="#XXXXXX" >
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Tracking Url <span style="color: red">*</span></td>
                                                                    <td>
                                                                        <input type="url" required name="tracking_url[]" class="form-control" placeholder="https://example/tracking/XXXXX" value="{{ $order->courier_url }}">
                                                                    </td>

                                                                </tr>
                                                                <tr>
                                                                    <td>Tracking Notes</td>
                                                                    <td>
                                                                        <input type="text" name="tracking_notes[]" class="form-control" placeholder="Notes for this fulfillment">
                                                                    </td>
                                                                </tr>
                                                            </tbody>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="block-content block-content-full text-right border-top">
                                                <button type="submit" class="btn btn-sm btn-primary" >Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                       

                    @endforeach
                </div>
            </div>
        </div>
       @endif

        <div class="col-md-12 my-2">
            <div class="card p-3">
                <div class="card-body">
                    <h4>Addresses</h4>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Company</th>
                                <th>Phone</th>
                                <th>Zip</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Country</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($order->shipping_address!==null)
                                <?php $ship = $order->shipping_address;?>
                                <tr>
                                    <td>Shipping Address</td>
                                    <td>
                                        @if(isset($ship['first_name'])) {{$ship['first_name']}} @endif
                                        @if(isset($ship['last_name'])) {{$ship['last_name']}} @endif
                                    </td>
                                    <td>
                                        @if(isset($ship['address1'])) {{$ship['address1']}} @endif
                                    </td>
                                    <td>
                                        @if(isset($ship['company'])) {{$ship['company']}} @endif
                                    </td>
                                    <td>
                                        @if(isset($ship['phone'])) {{$ship['phone']}} @endif
                                    </td>
                                    <td>
                                        @if(isset($ship['zip'])) {{$ship['zip']}} @endif
                                    </td>
                                    <td>
                                        @if(isset($ship['city'])) {{$ship['city']}} @endif
                                    </td>
                                    <td>
                                        @if(isset($ship['province'])) {{$ship['province']}} @endif
                                    </td>
                                    <td>
                                        @if(isset($ship['country'])) {{$ship['country']}} @endif
                                    </td>
                                </tr>

                            @endif
                            @if($order->billing_address!==null)
                                <?php $bill = $order->billing_address;?>
                                <tr>
                                    <td>Billing Address</td>
                                    <td>
                                        @if(isset($bill['first_name'])) {{$bill['first_name']}} @endif
                                        @if(isset($bill['last_name'])) {{$bill['last_name']}} @endif
                                    </td>
                                    <td>
                                        @if(isset($bill['address1'])) {{$bill['address1']}} @endif
                                    </td>
                                    <td>
                                        @if(isset($bill['company'])) {{$bill['company']}} @endif
                                    </td>
                                    <td>
                                        @if(isset($bill['phone'])) {{$bill['phone']}} @endif
                                    </td>
                                    <td>
                                        @if(isset($bill['zip'])) {{$bill['zip']}} @endif
                                    </td>
                                    <td>
                                        @if(isset($bill['city'])) {{$bill['city']}} @endif
                                    </td>
                                    <td>
                                        @if(isset($bill['province'])) {{$bill['province']}} @endif
                                    </td>
                                    <td>
                                        @if(isset($bill['country'])) {{$bill['country']}} @endif
                                    </td>
                                </tr>
                            @endif
                            @if($order->shipping_address==null && $order->billing_address==null)
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <h3 class="my-5">
                                            No Address Found!
                                        </h3>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
