@extends('layouts.admin')
@section('content')

    <div class="row m-t-15">
        <div class="col-sm-2 col-md-1">
            <h3 ><b>Order </b></h3>
        </div>
        <div class=" col-sm-6 col-md-6 row">
            <h3 ><b>{{ $order->name }}</b></h3>
            <div>
                <span class="badge badge-pill ml-4" style="background: {{$designer->background_color}}; color: {{$designer->color}}">{{$designer->name}}</span>
            </div>
            @if($exist == false)
                <button onclick="window.location.href='{{route('orders.sync.order',[
                                                'id' =>$order->id,
                                                'designer_id'=>$designer->id
                                                ])}}'"
                        class="btn btn-sm font-14 text-center justify-content-center btn-warning m-l-20">
                    <i class="text-white font-20 mdi mdi-sync"></i> Sync New Order
                </button>
            @else
                <button class="btn btn-sm font-14 text-center justify-content-center btn-success m-l-20">
                    <i class="text-white font-20 mdi mdi-checkbox-marked-circle"></i> Synced
                </button>
            @endif

        </div>


    </div>
    <div class="row pt-4">
        <div class="col-sm-6  col-md-5">
            <div class="card">
                <div class="card-block">
                    <div class="flexing">
                        <div class="col-md-10">
                            <h6><b>Notes</b></h6>
                        </div>

                    </div>
                    <div class="flexing ">
                        @if($order->note)
                            <p>{{ $order->note }}</p>
                        @else
                            <p>No notes from customer</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card p-3">
                @foreach($order->line_items as $index => $product)
                    @if($product->quantity == 1)
                        <div class="row p-3">
                            <div class="col-sm-12 col-md-12 p-0 card">
                                <div class="card-header bg-lite" style="padding-bottom: 26px"> <b>Design: {{ $order->name }}_{{$index+1}}</b></div>
                                <div class="row">
                                    <div class="col-sm-6 col-md-7 border-right">
                                        <div class="tittle p-3">
                                            <h5 ><b>{{ $product->name }}</b></h5>
                                        </div>

                                        @foreach($product->properties as $property)
                                            @if(strtolower($property->name) == 'style' || strtolower($property->name) == 'style2')
                                                <div class="row m-3">
                                                    <h6 class="pt-1"> Style : </h6>
                                                    @if(count($categories) > 0)
                                                        <?php
                                                        $styling = $categories->where('name', $property->value)->first()
                                                        ?>
                                                        @if($styling!==null)
                                                            <div class="pt-1 ml-2 "
                                                                 style="background:{{$styling->color}}">
                                                                <h6 class="pr-2 pl-2 text-white pt-1">
                                                                    <b>{{ $property->value}}</b></h6>
                                                            </div>
                                                        @else
                                                            <div class="pt-1 ml-2 btn-blue">
                                                                <h6 class="pr-2 pl-2 pt-1">
                                                                    <b>{{ $property->value}}</b></h6>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="pt-1 ml-2 btn-blue">
                                                            <h6 class="pr-2 pl-2 pt-1"><b>{{ $property->value}}</b> </h6>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if($property->name == 'How Many Pets?')
                                                <div class="row m-3">
                                                    <h6 class="pt-1"> Pets : </h6>
                                                    <div class="pt-1 ml-2  ">
                                                        <h6 class="pr-2 pl-2 pt-1"><b>{{ $property->value }}</b> </h6>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach


                                        @foreach($product->properties as $property)
                                            @if($property->name == '_io_uploads' ||$property->name == '_Uploaded Image' || $property->name == '_Uploaded Image 1')
                                                <div class="row  m-3">
                                                    <div class="col-sm-12 col-sm-6 justify-content-center" >
                                                        <a class="btn btn-rounded btn-purple"  target="_blank" href="{{ $property->value }}">Download Pet Photo</a>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                    </div>


                                    @foreach($product->properties as $property)
                                        @if($property->name == '_io_uploads' || $property->name == '_Uploaded Image'|| $property->name == '_Uploaded Image 1')
                                            <div class=" col-sm-6 col-md-5" align="center">
                                                <div class="mt-4 pr-2">
                                                    <img src="{{ $property->value }}" width="100%" height="auto" style="margin-bottom: 15px">
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach


                                </div>

                            </div>
                        </div>
                    @else

                        @php
                            $check_only_one_image = [];
        $check_multiple_images = [];
        $related_images_name = [];

        for ($i = 1; $i <= $product->quantity; $i++) {
            array_push($related_images_name, '_Uploaded Image ' . $i);
        }
        foreach ($product->properties as $key => $value) {

            if (in_array($value->name, $related_images_name)) {
                array_push($check_multiple_images, [
                    $key => $value
                ]);
            }
            if (in_array($value->name, ['_Uploaded Image'])) {
                array_push($check_only_one_image, [
                    $key => $value
                ]);
            }

        }
                        @endphp

                        @for($i=1;$i<=$product->quantity;$i++)

                            @php
                             $copy_multiple =   $check_multiple_images;
                            @endphp


                            <div class="row p-3">
                                <div class="col-sm-12 col-md-12 p-0 card">
                                    <div class="card-header bg-lite" style="padding-bottom: 26px"> <b>Design: {{ $order->name }}_{{$index+1}}</b></div>
                                    <div class="row">
                                        <div class="col-sm-6 col-md-7 border-right">
                                            <div class="tittle p-3">
                                                <h5 ><b>{{ $product->name }}</b></h5>
                                            </div>

                                            @foreach($product->properties as $property)
                                                @if($property->name == 'Style' || $property->name == 'Style2')
                                                    <div class="row m-3">
                                                        <h6 class="pt-1"> Style : </h6>
                                                        @if(count($categories) > 0)
                                                            @foreach($categories as $category)
                                                                @if($category->name ==  $property->value)
                                                                    <div class="pt-1 ml-2 " style="background:{{$category->color}}">
                                                                        <h6 class="pr-2 pl-2 text-white pt-1"><b>{{ $property->value}}</b> </h6>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <div class="pt-1 ml-2 btn-blue">
                                                                <h6 class="pr-2 pl-2 pt-1"><b>{{ $property->value}}</b> </h6>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif

                                                @if($property->name == 'How Many Pets?')
                                                    <div class="row m-3">
                                                        <h6 class="pt-1"> Pets : </h6>
                                                        <div class="pt-1 ml-2  ">
                                                            <h6 class="pr-2 pl-2 pt-1"><b>{{ $property->value }}</b> </h6>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach


                                            @if(count($check_only_one_image) > 0)
                                            @foreach($product->properties as $property)
                                                @if($property->name == '_io_uploads' ||$property->name == '_Uploaded Image')
                                                    <div class="row  m-3">
                                                        <div class="col-sm-12 col-sm-6 justify-content-center" >
                                                            <a class="btn btn-rounded btn-purple"  target="_blank" href="{{ $property->value }}">Download Pet Photo</a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                                @else

                                                @php
                                                $data = array_pop($check_multiple_images);
                                                foreach($data as $d){
                                                    $source = $d->value;
                                                }

                                                array_push($check_multiple_images,$data);

                                                @endphp

                                                <div class="row  m-3">
                                                    <div class="col-sm-12 col-sm-6 justify-content-center" >
                                                        <a class="btn btn-rounded btn-purple"  target="_blank" href="{{ $source}}">Download Pet Photo</a>
                                                    </div>
                                                </div>

                                            @endif

                                        </div>

                                        @if(count($check_only_one_image) > 0)
                                        @foreach($product->properties as $property)
                                            @if($property->name == '_io_uploads' || $property->name == '_Uploaded Image'|| $property->name == '_Uploaded Image 1')
                                                <div class=" col-sm-6 col-md-5" align="center">
                                                    <div class="mt-4 pr-2">
                                                        <img src="{{ $property->value }}" width="100%" height="auto" style="margin-bottom: 15px">
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                            @else
                                            @php
                                                $data = array_pop($copy_multiple);
                                                foreach($data as $d){
                                                    $source = $d->value;
                                                }

                                                array_push($copy_multiple,$data);

                                            @endphp
                                            <div class=" col-sm-6 col-md-5" align="center">
                                                <div class="mt-4 pr-2">
                                                    <img src="{{ $source }}" width="100%" height="auto" style="margin-bottom: 15px">
                                                </div>
                                            </div>
                                        @endif


                                    </div>

                                </div>
                            </div>
                        @endfor
                    @endif

                @endforeach
            </div>
        </div>
    </div>
@endsection
