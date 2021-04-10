@extends('layouts.admin')
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
                @foreach($products as $product)
                    <div class="col-md-4">
                        <div class="card">

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
