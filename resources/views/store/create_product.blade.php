@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="{{asset('css/summernote.min.css')}}">
<link rel="stylesheet" href="{{asset('dropzone/dropzone.min.css')}}">
@endsection

@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-3 pb-3">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h5 my-2">
                    Add New Product
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Products</a>
                        </li>

                        <li class="breadcrumb-item">Add New</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <form id="create_product_form" action="{{route('shopify.products.store',$product->id)}}" class="form-horizontal {{--push-30-t--}} push-30" method="post" enctype="multipart/form-data">
        @csrf
        <div class="content">
            <div class="row mb2">
                <div class="col-sm-12 text-right mb-3">
                    <a href="/" class="btn btn-default btn-square ">Discard</a>
                    <button class="btn btn-primary btn-square submit-button">Save</button>
                </div>
            </div>
            <!-- Info -->
            <div class="row">
                <div class="col-sm-8">
                    <div class="block">
                        <div class="block-content block-content-full">

                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="product-name">Title</label>
                                    <input class="form-control" type="text" value="{{$product->title}}" id="product-name" name="title"
                                           placeholder="Short Sleeve Shirt" required>
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <div class="form-material form-material-primary">
                                        <label>Long Description</label>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    {{-- @dd($product) --}}
                                    <textarea class="summernote" name="description" 
                                              placeholder="Please Enter Description here !">{{$product->description}}</textarea>
                                </div>
                            </div>
                            <h3 class="block-title">Size</h3>
                                    <div class="form-group">
                                        <div class="col-xs-12 push-10">
                                            <div class="custom-control custom-checkbox d-inline-block">
                                                <input type="checkbox" name="variants" checked class="custom-control-input" id="val-terms"  value="1">
                                                <label class="custom-control-label" for="val-terms">Attach size guide to product description</label>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                    </div>
                    {{-- <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Images</h3>
                        </div>
                        <div class="block-content">
                            <div class="row" >
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Images</label>
                                        <input type="file" name="images[]" multiple class="multipleimages form-control"/>
                                    </div>
                                    <div class="form-group images-preview">
                                        <div class="row">
            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Artwork</h3>
                            <p>If you upload artwork here, the upcoming order containing the product will have product artwork by default</p>
                        </div>
                        <div class="block-content">
                            <div class="row">
                                <div class="col-md-12 my-2">
                                    <a href="{{asset($product->artworks)}}"  class="btn btn-primary">Download Artworks Template</a>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Artworks Template Files</label>
                                        <input type="file" name="artworks[]" multiple class="multipleartworks form-control"/>
                                    </div>
                                    <div class="form-group artworks-preview">
                                        <div class="row">
            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Product Gallery</h3>
                            <p>Maximum upload file size: 4MB</p>
                        </div>
                        <div class="block-content">
                            <div class="row">
                                <div class="col-md-12 my-2">
                                    <a href="{{asset($product->mockups)}}" target="_blank" class="btn btn-primary">Download Mockup Template</a>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Mockup Template Files</label>
                                        <input type="file" name="mockups[]" multiple class="multiplemockups form-control"/>
                                    </div>
                                    <div class="form-group mockups-preview">
                                        <div class="row">
            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Pricing / Shipping / Inventory</h3>
                        </div>
                        <div class="block-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="number" step="any" value="" class="form-control" name="price" placeholder="$ 0.00" required>
                                    </div>
                                </div>
                                <div class="col-md-6 d-none">
                                    <div class="form-group">
                                        <label>Cost Per Item</label>
                                        <input type="number" step="any" value="0" class="form-control" name="cost"
                                               placeholder="$ 0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        XPrintee Price 
                                        <span class="d-block">(Include Shipping)</span>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        Profit
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        Region
                                    </div>
                                </div>
                                @foreach ($product->price as $index=>$price)
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input readonly id="prev_price_{{$index}}" disabled value="{{$price->price}}" class="form-control prev-prices"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input readonly id="profit_{{$index}}" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{$price->region}}</label>
                                    </div>
                                </div>
                                    
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="col-xs-12 ">
                                            <label>Quantity</label>
                                            <input type="number" step="any" class="form-control" name="quantity" placeholder="0" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="col-xs-12 ">
                                            <label>Weight</label>
                                            <input type="number" step="any" class="form-control" name="weight" placeholder="0.0Kg" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="col-xs-12 ">
                                            <label>SKU</label>
                                            <input type="text" class="form-control" name="sku" required>
                                            @error('sku')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">

                                        <div class="col-xs-12 ">
                                            <label>Barcode</label>
                                            <input type="text" class="form-control" name="barcode">
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                    

                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Variants</h3>
                        </div>
                        <div class="block-content">
                            <div class="form-group d-none">
                                <div class="col-xs-12 push-10">
                                    <div class="custom-control custom-checkbox d-inline-block">
                                        <input type="checkbox" name="variants" class="custom-control-input" id="val-terms"  value="1">
                                        <label class="custom-control-label" for="val-terms">This product has multiple options, like
                                            different sizes or colors</label>
                                    </div>
                                </div>
                            </div>

                            <div class="variant_options">
                                <hr>
                                <h3 class="font-w300">Options</h3>
                                <br>
                                <div class="form-group">
                                    <div class="col-xs-12 push-10">
                                        <h5>Option 1</h5>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <input readonly type="text" value="Size" class="form-control" placeholder="Attribute Name" name="attribute1">
                                            </div>
                                            <div class="col-sm-9">
                                                <h3 class="font-medium">{{str_replace(',',' , ',$product->sizes)}}</h6>
                                                {{-- <input class="js-tags-options options-preview form-control d-none" value="{{$product->sizes}}" type="text"
                                                       id="product-meta-keywords" name="option1" value=""> --}}
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-light btn-square option_btn_1 mt-2">
                                            Add another option
                                        </button>
                                    </div>
                                </div>
                                <div class="option_2" style="display: none;">
                                    <hr>
                                    <div class="form-group">
                                        <div class="col-xs-12 push-10">
                                            <h5>Option 2</h5>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control" placeholder="Attribute Name" name="attribute2">
                                                </div>
                                                <div class="col-sm-9">
                                                    <input class="js-tags-options options-preview form-control" type="text"
                                                           id="product-meta-keywords" name="option2">
                                                </div>
                                            </div>
                                            <button type="button"
                                                    class="btn btn-light btn-square option_btn_2 mt-2">Add another
                                                option
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="option_3" style="display: none;">
                                    <hr>
                                    <div class="form-group">
                                        <div class="col-xs-12 push-10">
                                            <h5>Option 3</h5>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control" placeholder="Attribute Name" name="attribute3">
                                                </div>
                                                <div class="col-sm-9">
                                                    <input class="js-tags-options options-preview form-control" type="text"
                                                           id="product-meta-keywords" name="option3">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="variants_table" style="display: none;">
                                    <hr>
                                    <h3 class="block-title">Preview</h3>
                                    <br>
                                    <div class="form-group">
                                        <div class="col-xs-12 push-10">
                                            <table class="table table-hover table-responsive">
                                                <thead>
                                                <tr>
                                                    <th style="width: 10%;">Title</th>
                                                    <th style="width: 20%;">Price</th>
                                                    <th style="width: 23%;">Cost</th>
                                                    <th style="width: 10%;">Quantity</th>
                                                    <th style="width: 20%;">SKU</th>
                                                    <th style="width: 20%;">Barcode</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="block">
                        <div class="block-header">
                            <div class="block-title">
                                Status
                            </div>
                        </div>
                        <div class="block-content pt-0">
                            <div class="form-group">
                                <div class="custom-control custom-radio mb-1">
                                    <input type="radio" class="custom-control-input" id="example-radio-customPublished" name="status" value="1" checked="">
                                    <label class="custom-control-label" for="example-radio-customPublished">Published</label>
                                </div>
                                <div class="custom-control custom-radio mb-1">
                                    <input type="radio" class="custom-control-input" id="example-radio-customDraft" name="status" value="0" >
                                    <label class="custom-control-label" for="example-radio-customDraft">Draft</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Organization</h3>
                        </div>
                        <div class="block-content pt-0">
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>Product Type</label>
                                    <input type="text" class="form-control" name="product_type"
                                           placeholder="eg. Shirts">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>Vendor</label>
                                    <input type="text" readonly value="XPrintee" class="form-control" name="vendor" placeholder="eg. Nike">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material form-material-primary">
                                        <label>Tags</label>
                                        <input type="text" class="js-tags-input form-control" id="product-meta-keywords" name="tags" value="">
                                    </div>
                                </div>
                            </div>
                            <p class="font-medium mt-3">This product's vendor will be set to "XPrintee". Please do not change this, or we may be unable to process your orders.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row ">
                        <div class="col-sm-12 text-right">
                            <hr>
                            <a href="/" class="btn btn-default btn-square ">Discard</a>
                            <button class="btn btn-primary btn-square submit-button">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection



@section('js')
<script src="{{asset('js/summernote.min.js')}}"></script>
<script src="{{asset('dropzone/dropzone.min.js')}}"></script>
<script>
    function getBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        }
    $(document).ready(function(){

        $('input[name=price]').on('input',function(){
            var price=$(this).val();
            price=parseFloat(price);

            $('.prev-prices').each((index,item)=>{
                var prevPrice=$(item).val();
                prevPrice=parseFloat(prevPrice);
                var profit=price-prevPrice;
                $('#profit_'+index).val(profit);
                if(profit>0)
                {
                    $('#profit_'+index).css('border-color','green');
                }else 
                {
                    $('#profit_'+index).css('border-color','red');
                }
            })
        })
        setTimeout(()=>{
            $('#val-terms').prop('checked',true);
        },1000);
        $('.summernote').summernote({
            height:'250px'
        });
    })
    // $('body').on('click','.dropzone',function () {
    //     $('.images-upload').trigger('click');
    // });


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

            $('.multipleimages').on('input',function(){
                $('.mockups-preview .row').empty();
                Array.prototype.forEach.call(this.files, function(file) {
                    getBase64(file).then(
                        data => {
                            $('.images-preview .row').append('<div class="col-md-4"><img src="'+data+'" width="100%" height="auto" class="img-thumbnail"/></div>');
                        }
                    );
                });

            }) 
            
            $('.multipleartworks').on('input',function(){
                $('.mockups-preview .row').empty();
                Array.prototype.forEach.call(this.files, function(file) {
                    getBase64(file).then(
                        data => {
                            $('.artworks-preview .row').append('<div class="col-md-4"><img src="'+data+'" width="100%" height="auto" class="img-thumbnail"/></div>');
                        }
                    );
                });

            }) 
    
    
    var dropzone = new Dropzone('#create_product_form', {
  previewTemplate: document.querySelector('#preview-template').innerHTML,
  parallelUploads: 2,
  thumbnailHeight: 120,
  thumbnailWidth: 120,
  maxFilesize: 3,
  filesizeBase: 1000,
  thumbnail: function(file, dataUrl) {
    console.log(file.previewElement,file,dataUrl);
    if (file.previewElement) {

        
      file.previewElement.classList.remove("dz-file-preview");
      var images = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
      for (var i = 0; i < images.length; i++) {
        var thumbnailElement = images[i];
        thumbnailElement.alt = file.name;
        thumbnailElement.src = dataUrl;
      }
      setTimeout(function() { file.previewElement.classList.add("dz-image-preview"); }, 1);
    }
  }

});


// Now fake the file upload, since GitHub does not handle file uploads
// and returns a 404

var minSteps = 6,
    maxSteps = 60,
    timeBetweenSteps = 100,
    bytesPerStep = 100000;

dropzone.uploadFiles = function(files) {
  var self = this;

  for (var i = 0; i < files.length; i++) {

    var file = files[i];
    totalSteps = Math.round(Math.min(maxSteps, Math.max(minSteps, file.size / bytesPerStep)));

    for (var step = 0; step < totalSteps; step++) {
      var duration = timeBetweenSteps * (step + 1);
      setTimeout(function(file, totalSteps, step) {
        return function() {
          file.upload = {
            progress: 100 * (step + 1) / totalSteps,
            total: file.size,
            bytesSent: (step + 1) * file.size / totalSteps
          };

          self.emit('uploadprogress', file, file.upload.progress, file.upload.bytesSent);
          if (file.upload.progress == 100) {
            file.status = Dropzone.SUCCESS;
            self.emit("success", file, 'success', null);
            self.emit("complete", file);
            self.processQueue();
            //document.getElementsByClassName("dz-success-mark").style.opacity = "1";
          }
        };
      }(file, totalSteps, step), duration);
    }
  }
}


</script>
@endsection