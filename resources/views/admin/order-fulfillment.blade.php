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
                                <th>Price</th>
                                <th>Quantity</th>
                            </tr>
                            </thead>
                            <tbody>
                            <form id="fulfilment_process_form" action="{{route('admin.order.fulfillment.process',$order->id)}}" method="post">
                                @csrf
                                @foreach($order->has_lineItems as $line)
                                    <tr>
                                        <td><img width="200px" height="100%" class="img img-thumbnail my-2" @if($line->image!==null) src="{{$line->image}}" @else src="{{asset('img/noimg.svg')}}" @endif/></td>
                                        <td>{{ucwords($line->title)}}</td>
                                        <td>{{$line->currency.$line->price}}</td>
                                        <td><div class="form-group">
                                                <div class="input-group">
                                                    <input type="hidden" name="item_id[]" value="{{$line->id}}">
                                                    <input type="number" class="form-control fulfill_quantity" min="0" max="{{$line->fulfillable_quantity}}" name="item_fulfill_quantity[]" value="{{$line->fulfillable_quantity}}">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        of {{$line->fulfillable_quantity}}
                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </form>
                            </tbody>
                        </table>
                        <div class="text-right">
                            <button class="btn btn-primary fulfill_items_btn ">Mark as Fulfilled</button>
                        </div>
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
    </div>

@endsection

@section('js')
    <script>
     $('.fulfill_quantity').change(function () {
        if($(this).val() > $(this).attr('max')){
            $(this).val($(this).attr('max'));
            alertify.error('Please provide correct quantity of item!');
        }
        var total_fulfillable = 0;
        $('body').find('.fulfill_quantity').each(function () {
            total_fulfillable = total_fulfillable + parseInt($(this).val()) ;
        });
        $('.fulfillable_quantity_drop').empty();
        $('.fulfillable_quantity_drop').append(total_fulfillable+' of '+$('.fulfillable_quantity_drop').data('total'));
        if(total_fulfillable === 0) {
            $('.atleast-one-item').show();
            $('.fulfill_items_btn').attr('disabled',true);
            $('.bulk_fulfill_items_btn').attr('disabled',true);

        }
        else{
            $('.atleast-one-item').hide();
            $('.fulfill_items_btn').attr('disabled',false);
            $('.bulk_fulfill_items_btn').attr('disabled',false);

        }

    });

    $('.fulfill_items_btn').click(function () {
        var total_fulfillable = 0;
        $('.fulfill_quantity').each(function () {
            total_fulfillable = total_fulfillable + parseInt($(this).val()) ;
        });
        if(total_fulfillable > 0) {
           $('#fulfilment_process_form').submit();
        }
        else{
            $('.atleast-one-item').hide();
            $('.fulfill_items_btn').attr('disabled',false);
        }
    });

    </script>
@endsection
