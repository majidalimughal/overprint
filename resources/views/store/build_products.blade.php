@extends('layouts.admin')
@section('content')
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3><strong>Products</strong></h3>
        </div>
        {{--        <div class="col-md-7 text-right">--}}
        {{--            <a class="btn btn-warning" href="{{route('orders.sync')}}"> Sync New Orders </a>--}}
        {{--        </div>--}}
    </div>


    <form action="{{route('available.products')}}">
        <div class="row">
            <div class="col-md-5">
                <input type="text" class="form-control" autocomplete="false" name="search" required>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
{{--            <div class="col-md-6">--}}
{{--                <a href="{{route('shopify.synchronize.products')}}" class="btn btn-primary float-right">Synchronize--}}
{{--                    Store Products</a>--}}
{{--            </div>--}}
        </div>


        </div>
    </form>

    <div class="orders p-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table v-middle" id="order_table">
                            <thead>
                            <tr class="bg-light ">

                                <th class="border-top-0">#</th>
                                <th class="border-top-0">Thumbnail</th>
                                <th class="border-top-0">Title</th>
                                <th class="border-top-0">Vendor</th>
                                <th class="border-top-0">Product Type</th>
                                <th class="border-top-0">Tags</th>
                                <th class="border-top-0">Variants</th>
                                <th class="border-top-0">Created At</th>
                            </tr>
                            </thead>
                            <tbody id="myTable">
                            @foreach($products as $key=>$product)
                                <tr @if($product->has_print_product!==null) class="bg-light" @endif>
                                    <td>{{($products->currentPage()-1)*20+$key+1}}</td>
                                    <td><img src="{{$product->image}}" width="70px" height="auto"
                                             class="img-thumbnail"/></td>
                                    <td>{{$product->title}}</td>
                                    <td>{{$product->vendor}}</td>
                                    <td>{{$product->product_type}}</td>
                                    <td class="text-center">
                                        @foreach(explode(',',$product->tags) as $tag)

                                            <span class="badge badge-warning">{{$tag}}</span>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        @foreach($product->variantsCount() as $variant)

                                            <span class="badge badge-success">{{$variant}}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{$product->created_at}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-right float-right"
                    {{ $products->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>

        </div>
    </div>
    </div>

@endsection
