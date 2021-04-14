@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/summernote.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/materialize-tags.min.css')}}">
    {{--    <link rel="stylesheet" href="{{asset('css/dropzone.min.css')}}">--}}
    <style>
        .image-preview
        {
            height: 240px !important;
        }
    </style>
@endsection


@section('content')
    <form method="POST" action="{{route('admin.product.update',$product->id)}}" enctype="multipart/form-data">
        @csrf
        <div class="row page-titles">
            <div class="col-8 align-self-center">
                <h3><strong>Edit {{$product->title}}</strong></h3>
            </div>
            <div class="col-4 align-self-center">
                <button class="btn btn-primary float-right">Save</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Title</label>
                            <input required value="{{$product->title}}" name="title" type="text" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" required rows="10" class="form-control summernote" >{{$product->description}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Price</label>
                            <input required name="price"value="{{$product->price}}" type="number" step="0.01" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Thumbnail</label>
                            <input type="file" id="thumbnailimage"  name="thumbnail" class="form-control"/>
                        </div>
                        <div class="form-group image-preview ">
                            <img src="{{asset($product->thumbnail)}}" width="140px" height="auto" class="img-thumbnail"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Images</label>
                            <input type="file" name="images[]" multiple class="multipleImages form-control"/>
                        </div>
                        <div class="form-group images-preview">
                            <div class="row">
                                @foreach($product->images as $image)
                                    <div class="col-md-4"><img src="{{asset($image)}}" width="100%" height="auto" class="img-thumbnail"/></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Mockups</label>
                            <input type="file" name="mockups[]" multiple class="multiplemockups form-control"/>
                        </div>
                        <div class="form-group mockups-preview">
                            <div class="row">
                                @foreach($product->mockups as $image)
                                    <div class="col-md-4"><img src="{{asset($image)}}" width="100%" height="auto" class="img-thumbnail"/></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Sizes</label>
                            <input type="text" value="{{$product->sizes}}" required data-role="materialtags" class="form-control" name="sizes"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Size Guide</label>
                            <textarea name="sizeguide" required rows="10" class="form-control summernote" >{{$product->sizeguide}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Design Template</label>
                            <textarea name="designtemplate" required rows="10" class="form-control summernote" >{{$product->designtemplate}}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Shipping Details</label>
                            <textarea name="shippingdetails" required rows="10" class="form-control summernote" >{{$product->shippingdetails}}</textarea>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </form>
@endsection


@section('scripts')
    <script src="{{asset('js/summernote.min.js')}}"></script>
    <script src="{{asset('js/materialize-tags.min.js')}}"></script>
    <script src="{{asset('js/typeahead/typeahead.bundle.min.js')}}"></script>
    {{--    <script src="{{asset('js/dropzone-amd-module.min.js')}}"></script>--}}
    {{--    <script src="{{asset('js/dropzone.min.js')}}"></script>--}}

    <script>
        function getBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        }
        $(document).ready(function() {
            $('.summernote').summernote({
                height:'250px'
            });
            // $(".dropzone").dropzone({ url: "/file/post" });
            $('.multipleImages').on('input',function(){
                $('.images-preview .row').empty();
                Array.prototype.forEach.call(this.files, function(file) {
                    getBase64(file).then(
                        data => {
                            $('.images-preview .row').append('<div class="col-md-4"><img src="'+data+'" width="100%" height="auto" class="img-thumbnail"/></div>');
                        }
                    );
                });

            })
            $('.multiplemockups').on('input',function(){
                $('.mockups-preview .row').empty();
                Array.prototype.forEach.call(this.files, function(file) {
                    getBase64(file).then(
                        data => {
                            $('.mockups-preview .row').append('<div class="col-md-4"><img src="'+data+'" width="100%" height="auto" class="img-thumbnail"/></div>');
                        }
                    );
                });

            })
            $('#thumbnailimage').on('input',function(){
                var file = this.files[0];
                getBase64(file).then(
                    data => {
                        $('.image-preview img').attr('src',data);
                        $('.image-preview img').removeClass('display_none');
                    }
                );
            })
        })
    </script>
@endsection
