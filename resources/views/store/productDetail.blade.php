@extends('layouts.admin')
@section('content')
    <div class="row page-titles p-4">
        <div class="col-md-5 col-8 align-self-center">
            <h2><strong>{{$product->title}}
                    {{--                    <span class="mdi mdi-check-circle text-warning"></span>--}}
                </strong></h2>
        </div>
    </div>


    <div class="">
        <div class="row">
            <div class="col-md-9">
                <div class="card p-2">
                    <div class="card-header">
                        <h4>Description</h4>
                    </div>
                    <div class="card-body ">
                        <div class="p-4 border-0">
                            {!! $product->body_html !!}
                        </div>
                    </div>
                </div>
                <div class="card p-2">
                    <div class="card-header">
                        <h4>Media</h4>
                    </div>
                    <div class="card-body ">
                        <div class="p-4 border-0">
                            <div class="row text-center">
                                @foreach($product->images as $image)
                                    <div class="col-md-3 p-0">
                                        <img class="img-responsive" src="{{$image->src}}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-2">
                    <div class="card-header">
                        <h4>Variants</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <thead class="bg-light">
                            <tr>
                                <th>Thumbnail</th>
                                <th>Title</th>
                                <th>Sku</th>
                                <th>Options</th>
                                <th>Available Quantity</th>
                                <th>Inventory Policy</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($product->variants as $variant)
                                <tr class="text-center">
                                    <td><img src="{{$variant->image}}" width="100px"/></td>
                                    <td>{{$variant->title}}</td>
                                    <td>{{$variant->sku}}</td>
                                    <td class="text-center">
                                        @if($variant->option1!==null)
                                            <span class="badge badge-primary">{{$variant->option1}}</span>
                                        @endif
                                        @if($variant->option2!==null)
                                            <span class="badge badge-primary">{{$variant->option2}}</span>
                                        @endif
                                        @if($variant->option3!==null)
                                            <span class="badge badge-primary">{{$variant->option3}}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{$variant->inventory_quantity}}</td>
                                    <td class="text-center">{{ucwords($variant->inventory_policy)}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-header">
                        <h4>Product Tags</h4>
                    </div>
                    <div class="card-body px-4">
                        @foreach(explode(',',$product->tags) as $tag)
                            <span class="badge badge-warning">{{$tag}}</span>
                        @endforeach
                    </div>
                </div>
                <div class="card p-4">
                    <div class="card-header">
                        <h4>Product Vendor</h4>
                    </div>
                    <div class="card-body px-4">
                        <h6>{{$product->vendor}}</h6>
                    </div>
                </div>
                <div class="card p-4">
                    <div class="card-header">
                        <h4>Product Type</h4>
                    </div>
                    <div class="card-body px-4">
                        <h6>{{$product->product_type}}</h6>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
