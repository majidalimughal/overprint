@extends('layouts.admin')



@section('content')
<div class="bg-body-light">
    <div class="content content-full pt-3 pb-3">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill text-center h5 my-2">
                Contact Us
            </h1>

            
        </div>
        <form action="{{route('contact.us.send')}}" method="POST">
        @csrf
        <div class="container">
            <div class="row my-5 py-5">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Name</label>
                        <input autocomplete="no-fill" class="form-control" name="name" required/>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input autocomplete="no-fill" class="form-control" name="email" required type="email"/>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Message</label>
                        <textarea autocomplete="no-fill" class="form-control" rows="10" name="message"></textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <button class="btn btn-primary btn-lg w-100 btn-full">Send</button>
                </div>
            </div>
        </div>
    </form>
    </div>
</div>

@endsection