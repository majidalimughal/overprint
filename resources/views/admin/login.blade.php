@extends('layouts.admin')
@section('content')
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3><strong>Management</strong></h3>

        </div>

    </div>
    <div class="row mt-5 justify-content-center">
        <div class="col-md-4" >
            <div class="card">

                <div class="card-header">
                    <h4><b> Log in </b></h4>
                    <h6>Continue to your store</h6>
                </div>
                <div class="card-block">
                    <form>
                        <div class="form-group">
                            <label> <b>Store Address</b></label>
                            <input class="form-control" name="store" placeholder="myshop.myshopify.com" >
                        </div>
                        <button class="btn btn-primary btn-next"> Next</button>
                    </form>
                </div>

            </div>

        </div>

    </div>
@endsection
