@extends('layouts.main')


@section('content')
    <div class="row page-titles">
        <div class="col-8 align-self-center">
            <h3><strong>Products</strong></h3>
        </div>
        <div class="col-4 align-self-center">
            <a href="{{route('admin.product.create')}}" class="btn btn-primary float-right">Create Product</a>
        </div>
    </div>

    <div class="orders">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table v-middle">
                            <thead>
                            <tr class="bg-light ">
                                <th class="border-top-0">ID</th>
                                <th class="border-top-0">Thumbnail</th>
                                <th class="border-top-0">Title</th>
                                <th class="border-top-0">Price</th>
                                <th class="border-top-0">Created on Stores</th>
                                <th class="border-top-0">Product Sold</th>
                                <th class="border-top-0">Total Sale</th>
                                <th class="border-top-0">Created At</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="myTable">
                            @foreach($products as $key=>$product)
                                <tr class="text-center">
                                    <td>{{($products->currentPage()-1)*20+$key+1}}</td>
                                    <td>@if($product->thumbnail) <img src="{{asset($product->thumbnail)}}" class="img-thumbnail" width="70px" height="auto"/> @endif</td>
                                    <td><a href="{{route('admin.product.edit',$product->id)}}">{{$product->title}}</a></td>
                                    <td> ${{$product->getAvgPrice()}}</td>
                                    <td>{{count($product->has_StoreProduct)}}</td>
                                    <td>{{$product->hasSale->sum('quantity')}}</td>
                                    <td>USD {{$product->hasSale->sum('sale')}}</td>
                                    <td>{{$product->created_at->format('Y-m-d')}}</td>
                                    <td><a href="{{route('admin.product.delete',$product->id)}}" class="btn btn-danger btn-sm">Delete</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center"
                         style="display: flex;justify-content: center;align-items: center;margin: 10px;">
                        {{ $products->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection


@section('scripts')

@endsection
