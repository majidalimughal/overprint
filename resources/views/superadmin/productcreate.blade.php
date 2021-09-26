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
    <form method="POST" action="{{route('admin.product.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="row page-titles">
            <div class="col-8 align-self-center">
                <h3><strong>Create new Product</strong></h3>
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
                            <input required name="title" type="text" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" required rows="10" class="form-control summernote" ></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">Region</div>
                            <div class="col-md-5">Price</div>
                        </div>
                        <div class="prices">
                            <div class="row">
                                <div class="col-md-5">
                                    <input required name="region[]" value="Whole World" required class="form-control"/>
                                </div>
                                <div class="col-md-5">
                                    <input required name="price[]" type="number" required step="0.01" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add_price" class="btn btn-primary btn-sm mt-2">Add</button>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card p-5">
                    <div class="form-group">
                        <label>Thumbnail</label>
                        <input type="file" id="thumbnailimage" required name="thumbnail" class="form-control"/>
                    </div>
                    <div class="form-group image-preview display_none">
                        <img src="{{asset('c.png')}}" width="140px" height="auto" class="img-thumbnail display_none"/>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="form-group">
                            <label>images</label>
                            <input type="file" name="images[]" multiple class="multipleImages form-control"/>
                        </div>
                        <div class="form-group images-preview">
                            <div class="row">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Artwork Template Files</label>
                            <input type="file" name="artworks" multiple class="form-control"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Mockup Template Drive link</label>
                            <input type="text" name="mockups" required class="form-control"/>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Sizes</label>
                            <input type="text" required data-role="materialtags" class="form-control" name="sizes"/>
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
                            <textarea name="sizeguide" required rows="10" class="form-control summernote" ></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Design Template</label>
                            <textarea name="designtemplate" required rows="10" class="form-control summernote" ></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Shipping Details</label>
                            <textarea name="shippingdetails" required rows="10" class="form-control summernote" ></textarea>
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
            $('.multipleArtworks').on('input',function(){
                $('.mockups-preview .row').empty();
                Array.prototype.forEach.call(this.files, function(file) {
                    getBase64(file).then(
                        data => {
                            $('.artworks-preview .row').append('<div class="col-md-4"><img src="'+data+'" width="100%" height="auto" class="img-thumbnail"/></div>');
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
