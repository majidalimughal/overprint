@extends('layouts.admin')
@section('content')


    <div class="row page-titles">
        <div class="col-md-9 col-8 align-self-center">
            <h3><strong>Management</strong></h3>
        </div>
        <div class="col-md-3 " align="center">
            <button class="btn btn-rounded btn-purple " type="button" id="" data-toggle="modal" data-target="#exampleModal">
                Add Designer
            </button>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Designer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.designer.save') }}" method="POST">
                        <div class="modal-body">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12 ">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input class="form-control" name="name" type="text" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Text Color</label>
                                        <input class="form-control" name="color" type="color" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Background Color</label>
                                        <input class="form-control" name="background_color" type="color" required>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(session()->has('success'))
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning" role="alert" style="margin: 0">{{session('success')}}</div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 pl-5 pt-2 pr-0">
                    <h4>Manual designer picker </h4>
                    <div class="row">
                        <form action="{{route('admin.manual-picker')}}" method="POST" style="width: 100%">
                            @csrf
                            <div class="col-md-12 d-flex">
                                <div class="select-designers">
                                    <select required class="form-control" name="designer" >
                                        <option value="">Select Designer</option>
                                        @foreach($designers as $d)
                                            <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="select-orders">
                                    <select required class="form-control" name="start-order" >
                                        <option value="">Start Order</option>
                                        @foreach($orders as $o)
                                            <option value="{{$o->id}}">{{$o->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="select-orders">
                                    <select required class="form-control" name="end-order" >
                                        <option value="">End Order</option>
                                        @foreach($orders as $o)
                                            <option value="{{$o->id}}">{{$o->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="apply-button">
                                    <input type="submit" class="btn btn-secondary" value="Apply">
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
                <div class="col-md-6" >
                    <div class="row">
                        <div class="col-md-12 d-inline-block pt-1">
                            <form action="{{route('admin.designer.dashboard')}}" method="get">
                                <div class="form-group d-inline-block" style="width: 38%">
                                    <label><b>Starting</b></label>
                                    <input required name="start" value="{{$start}}" class="form-control" type="date">
                                </div>
                                <div class="form-group d-inline-block" style="width: 38%">
                                    <label><b>Ending</b></label>
                                    <input name="end" value="{{$end}}" required class="form-control" type="date">
                                </div>
                                <div class="form-group d-inline-block" style="width: 18%">
                                    <input class=" form-control btn btn-sm text-white btn-purple btn-rounded" type="submit" value="Filter">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    @foreach($designers as $index => $designer)
        <div class="pl-5">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-1">
                        <span class="badge badge-pill bdg-sucss mt-1" data-toggle="modal" data-target="#designerLogin{{$index}}" style="border-color:{{ $designer->background_color }};background-color:{{ $designer->background_color }};color:{{ $designer->color }};cursor: pointer">{{ $designer->name }}</span>
                    </div>

                    <div class="modal fade" id="designerLogin{{$index}}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Designer Information</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input class="form-control" value="{{$designer->name}}@boompup.com" type="text" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12 ">
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input class="form-control" value="{{str_replace(' ', '', $designer->name)}}@1234" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <label class="switching">
                            <input type="checkbox" @if($designer->status == 1) checked @endif id="togBtn" data-route="{{route('admin.designer.status')}}" data-method="GET" data-designer="{{$designer->id}}">
                            <span class="slides rounds"></span>
                        </label>
                    </div>
                    <div class="col-md-1 pt-2">
                        <h6 class="status @if($designer->status == 1) text_active @else text-danger font-bold @endif"><b>  @if($designer->status == 1) Active @else Disabled @endif</b></h6>
                    </div>
                    <div class="col-md-3 offset-6">
                        <button class="btn btn-danger text-white" onclick="window.location.href='{{route('admin.designer.delete',$designer->id)}}'">Delete</button>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-5">
                    <div class="card">
                        <h5 class="card-header"><b>Reviews</b>

                        </h5>
                        <div class="card-block" >
                            <div class="row">
                                <div class="col-md-8" align="right">
                                    <div class='rating-stars'>
                                        <input type="hidden" name="rating" value="{{$ratings[$index]}}" class="input_rating">
                                        <ul id='stars' style="margin-bottom: 5px">
                                            <li class='star' title='Poor' data-value='1'>
                                                <i class='fa fa-star fa-fw' ></i>
                                            </li>
                                            <li class='star' title='Fair' data-value='2'>
                                                <i class='fa fa-star fa-fw ' ></i>
                                            </li>
                                            <li class='star' title='Good' data-value='3'>
                                                <i class='fa fa-star fa-fw ' ></i>
                                            </li>
                                            <li class='star' title='Excellent' data-value='4'>
                                                <i class='fa fa-star fa-fw ' ></i>
                                            </li>
                                            <li class='star' title='WOW!!!' data-value='5'>
                                                <i class='fa fa-star fa-fw '></i>
                                            </li>
                                        </ul>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h4 class="rating"><b>{{number_format($ratings[$index],2)}}</b></h4>
                                </div>
                            </div>
                            <div class="row mt-5" align="center">
                                <div class="col-md-12" align="center">
                                    <div class="dropdown">
                                        <button class="btn btn-rounded btn-blue text-center text-white" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            @if(count($designer->has_reviews) > 0)
                                                <span class="">Reviews</span>
                                            @else
                                                <span class="">No Reviews</span>
                                            @endif
                                        </button>
                                        @if(count($designer->has_reviews) > 0)
                                            <div class="dropdown-menu edit_menu" aria-labelledby="dropdownMenuButton">
                                                <?php
                                                if($start != null && $end != null){

                                                    $reviews =  $designer->has_reviews()->where('created_at','>=',$start)->where('created_at','<=',$end)->get();
                                                }
                                                else{
                                                    $reviews = $designer->has_reviews;
                                                }
                                                ?>
                                                @foreach($reviews as $r)
                                                    <div class="row" style="padding: 10px">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class='rating-stars'>
                                                                        <input type="hidden" name="rating" value="{{$r->rating}}" class="input_rating">
                                                                        <ul id='stars' style="margin-bottom: 5px">
                                                                            <li class='star' title='Poor' data-value='1'>
                                                                                <i class='fa fa-star fa-fw' style="font-size: 13px"></i>
                                                                            </li>
                                                                            <li class='star' title='Fair' data-value='2'>
                                                                                <i class='fa fa-star fa-fw ' style="font-size: 13px"></i>
                                                                            </li>
                                                                            <li class='star' title='Good' data-value='3'>
                                                                                <i class='fa fa-star fa-fw ' style="font-size: 13px"></i>
                                                                            </li>
                                                                            <li class='star' title='Excellent' data-value='4'>
                                                                                <i class='fa fa-star fa-fw ' style="font-size: 13px"></i>
                                                                            </li>
                                                                            <li class='star' title='WOW!!!' data-value='5'>
                                                                                <i class='fa fa-star fa-fw ' style="font-size: 13px"></i>
                                                                            </li>
                                                                        </ul>

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <h6>{{date_create($r->created_at)->format('Y-m-d H:i a')}}</h6>
                                                                </div>
                                                                @if(isset($r->has_order->bill_first_name))
                                                                <div class="col-md-4 row">
                                                                    <span class="mdi mdi-checkbox-marked-circle"></span>
                                                                    <h6 class="text-primary p-1">{{$r->has_order->bill_first_name}}</h6>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 ">
                                                            <h6 class="pt-3 pb-3 pl-3">{{$r->review}}</h6>
                                                            {{--<span class="border-bottom"></span>--}}
                                                        </div>
                                                    </div>
                                                    <hr>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card">

                        <h5 class="card-header"><b>Designs</b></h5>
                        <div class="card-block ">
                            <div class="design-orders" align="center">
                                <?php
                                if($start != null && $end != null){
                                    $count = 0;
                                    $orders  =  $designer->has_orders()->newQuery();
                                    $orders->whereHas('has_products',function ($q) use ($start,$end){
                                        $q->where('approved_date','>=',$start);
                                        $q->where('approved_date','<=',$end);
                                    });
                                    $count = count($orders->get());

                                }
                                else{
                                    $count = count($designer->has_orders);
                                }
                                ?>
                                <h3><b>{{$count}} Orders</b></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
