@extends('layouts.admin')

@section('styles')
    <style>
        .image-preview {
            width: 100%;
            height: 400px;
            background-size: cover !important;
            background-position: center !important;
            transition: all .2s;
        }
        .image-preview:hover{
            /*transform: scale(1.2);*/
        }
    </style>
@endsection
@section('content')
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3><strong>New Products</strong></h3>
        </div>
        {{--        <div class="col-md-7 text-right">--}}
        {{--            <a class="btn btn-warning" href="{{route('orders.sync')}}"> Sync New Orders </a>--}}
        {{--        </div>--}}
    </div>


{{--    <form action="{{route('available.products')}}">--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-5">--}}
{{--                <input type="text" class="form-control" autocomplete="false" name="search" required>--}}
{{--            </div>--}}
{{--            <div class="col-md-1">--}}
{{--                <button type="submit" class="btn btn-primary">Filter</button>--}}
{{--            </div>--}}
{{--            --}}{{--            <div class="col-md-6">--}}
{{--            --}}{{--                <a href="{{route('shopify.synchronize.products')}}" class="btn btn-primary float-right">Synchronize--}}
{{--            --}}{{--                    Store Products</a>--}}
{{--            --}}{{--            </div>--}}
{{--        </div>--}}

{{--    </form>--}}

    <div class="orders p-4">
        <div class="row">
            @if(count($products))
                @foreach($products as $key=>$product)
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="image-preview" style='background: url("{{asset($product->thumbnail)}}")'></div>
                                <div class="p-3">
                                    <h4>{{$product->title}}</h4>
                                    <h6>Available Sizes: @foreach(explode(',',$product->sizes) as $size)<span class="badge badge-purple">{{$size}}</span> @endforeach</h6>
                                    <h4><span class="font-20 font-weight-bold text-purple">$23</span><span class="font-14">/including shipping</span></h4>
                                </div>
                                <div class="modal fade" id="productinfo{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="exampleModalLongTitle">{{$product->title}}</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                ...
                                            </div>
{{--                                            <div class="modal-footer">--}}
{{--                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--                                                <button type="button" class="btn btn-primary">Save changes</button>--}}
{{--                                            </div>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white text-center">
                                <button class="btn btn-primary mx-1" data-toggle="modal" data-target="#productinfo{{$key}}">Product Info</button>
                                <button class="btn btn-primary mx-1">Create Product</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-md-12">
                    <h2 class="text-center my-5 py-5">There's no new products available in our catalog 😞</h2>
                </div>
            @endif
        </div>
        <div class="text-right float-right">
            {{ $products->links('vendor.pagination.bootstrap-4') }}
        </div>

    </div>

@endsection
