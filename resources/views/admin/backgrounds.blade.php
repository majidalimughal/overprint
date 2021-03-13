@extends("layouts.admin")
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h4 class="card-header">Background /Patterns</h4>
                <div class="card-block">
                    @if(session()->has('success'))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-warning" role="alert" style="margin-bottom: 10px">{{session('success')}}</div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <form action="{{ route('admin.background') }}" method="GET">
                                        <select  class="form-control select_ajax_reload" name="category">
                                            <option value="">All</option>
                                            @foreach($categories as $category)
                                                <option @if($request) @if($request->category == $category->id) selected @endif @endif value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                                <div class="col-md-8">
                                    <button class="btn delete-category-btn btn-rounded btn-warning" data-route="{{route('admin.category.delete')}}">
                                        Delete Category
                                    </button>
                                    <button class="btn btn-rounded btn-purple " type="button" id="" data-toggle="modal" data-target="#add_background">
                                        New Background
                                    </button>
                                </div>
                            </div>
                        </div>




                        <div class="modal fade" id="add_background" tabindex="-1" role="dialog" aria-labelledby="add_background" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel"><b>Add New Background</b></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form enctype="multipart/form-data" method="POST" action="{{ route('admin.background.save') }}">
                                        {{csrf_field()}}
                                        <div class="modal-body">

                                            <div class="form-group">
                                                <label><b>Name:</b></label>
                                                <input class="form-control" name="name" type="text" required>
                                            </div>
                                            <div class="form-group">
                                                <label><b>Category:</b></label>
                                                <select class="form-control" name="category_id" required>
                                                    <option value="">--Choose--</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label><b>Upload:</b></label>
                                                <div>
                                                    <input class="" name="image" accept="image/*" type="file" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 ">
                            <div class="row">
                                <div class="col-md-6" style="opacity: 0">
                                    <div class="dropdown ">
                                        <button class="btn btn-secondary dropdown-toggle form-control text-left" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Category
                                        </button>
                                        <div class="dropdown-menu categories" aria-labelledby="dropdownMenuButton">
                                            <input class="form-control" type="text" id="category_inp" placeholder="Add Category">
                                            @foreach($categories as $category)
                                                <a class="dropdown-item ">{{ $category->name }} <span class="mdi mdi-close closed"></span></a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-rounded btn-purple " type="button" id="" data-toggle="modal" data-target="#add_background_category">
                                        Add Category
                                    </button>


                                    <div class="modal fade" id="add_background_category" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="add_background_categoryLabel"><b>Add New Category</b></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.background.categories.save') }}" method="POST">
                                                    {{ csrf_field() }}
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label><b>Name:</b></label>
                                                            <input class="form-control" name="name" type="text" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><b>Color hexa:</b></label>
                                                            <input class="form-control" name="color" type="color" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-danger">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="row pt-2">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table v-middle drag-table" width="100%">

                                    <thead>
                                    <tr class="bg-light">
                                        <th></th>
                                        <th class="text-left">Name</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody data-route="{{route('admin.background.postion.update')}}">
                                    @foreach($backgrounds as $index =>  $background)
                                        <tr id="{{$index}}" data-id="{{$background->id}}" data-position="{{$background->position}}">
                                            <td class="text-left">
                                                <img src="{{asset($background->image)}}" width="80px" height="80px">
                                            </td>
                                            <td class="text-left">
                                                {{ $background->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $background->has_category->name }}
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('admin.background.delete', $background->id) }}"><span class="mdi mdi-close p-3"></span></a>
                                                <span class="mdi mdi-menu p-3"> </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center">
                                {{ $backgrounds->links() }}
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
