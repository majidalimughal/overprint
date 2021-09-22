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
                                    <div class="modal-dialog modal-lg modal-dialog-centered max-width-70" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="exampleModalLongTitle">{{$product->title}}</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                                            <ol class="carousel-indicators">
                                                                @foreach ($product->images as $index=>$image)
                                                                <li data-target="#carouselExampleIndicators" data-slide-to="{{$index}}" class="{{$index===0?'active':''}}"></li>
                                                                @endforeach
                                                              
                                                              {{-- <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                                              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li> --}}
                                                            </ol>
                                                            <div class="carousel-inner">
                                                              
                                                              @foreach ($product->images as $index=>$image)
                                                                <div class="carousel-item {{$index===0?'active':''}}">
                                                                    <img width="auto" height="500px" class="d-block w-100" src="{{asset($image)}}" alt="First slide">
                                                                </div>
                                                              @endforeach
                                                            </div>
                                                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                                              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                              <span class="sr-only">Previous</span>
                                                            </a>
                                                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                                              <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                              <span class="sr-only">Next</span>
                                                            </a>
                                                          </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h2>Product Description</h2>
                                                        <div class="product-description">
                                                            {!! $product->description!!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mt-5">
                                                        <!-- Tabs navs -->
<ul class="nav nav-tabs nav-fill mb-3" id="ex1" role="tablist">
    <li class="nav-item" role="presentation">
      <a
        class="nav-link active"
        id="ex2-tab-1"
        data-mdb-toggle="tab"
        href="#ex2-tabs-1-{{$product->id}}"
        role="tab"
        aria-controls="ex2-tabs-1"
        aria-selected="true"
        >Size Guide</a
      >
    </li>
    <li class="nav-item" role="presentation">
      <a
        class="nav-link"
        id="ex2-tab-2"
        data-mdb-toggle="tab"
        href="#ex2-tabs-2-{{$product->id}}"
        role="tab"
        aria-controls="ex2-tabs-2"
        aria-selected="false"
        >Design Template</a
      >
    </li>
    <li class="nav-item" role="presentation">
      <a
        class="nav-link"
        id="ex2-tab-3"
        data-mdb-toggle="tab"
        href="#ex2-tabs-3-{{$product->id}}"
        role="tab"
        aria-controls="ex2-tabs-3"
        aria-selected="false"
        >Shipping DEtails</a
      >
    </li>
  </ul>
  <!-- Tabs navs -->
  
  <!-- Tabs content -->
  <div class="tab-content" id="ex2-content">
    <div
      class="tab-pane fade show active"
      id="ex2-tabs-1-{{$product->id}}"
      role="tabpanel"
      aria-labelledby="ex2-tab-1"
    >
      {!!$product->sizeguide!!}
    </div>
    <div
      class="tab-pane fade"
      id="ex2-tabs-2-{{$product->id}}"
      role="tabpanel"
      aria-labelledby="ex2-tab-2"
    >
      {!!$product->designtemplate!!}
    </div>
    <div
      class="tab-pane fade"
      id="ex2-tabs-3-{{$product->id}}"
      role="tabpanel"
      aria-labelledby="ex2-tab-3"
    >
      {!!$product->shippingdetails!!}
    </div>
  </div>
  <!-- Tabs content -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white text-center">
                                <button class="btn btn-primary mx-1" data-toggle="modal" data-target="#productinfo{{$key}}">Product Info</button>
                                <a href="{{route('build.product.shopify',encrypt($product->id))}}" class="btn btn-primary mx-1">Create Product</a>
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
