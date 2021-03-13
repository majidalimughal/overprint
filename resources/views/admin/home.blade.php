@extends('layouts.admin')
@section('content')
    <div class="row page-titles">
        <div class="col-md-12 col-12" align="center">
            <h3 class=""><strong>Have a great design day, {{\Illuminate\Support\Facades\Auth::user()->name}}! </strong></h3>
        </div>
    </div>

    <div class="row justify-content-center" >
        <div class="col-md-5 pl-5 pr-5">
            <div class="card">
                <div class="card-block edit-block">
                    <h6 class="card-title"><strong>TOTAL NEW ORDERS</strong></h6>
                    <div class="material-icon-list-demo demo-inner">
                        <h4 class=""><strong>{{$new_orders}}</strong></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5  pl-5 pr-5">
            <div class="card">
                <div class="card-block edit-block">
                    <h6 class="card-title"><strong>TOTAL NEW DESIGN</strong></h6>
                    <div class="material-icon-list-demo demo-inner">
                        <h4 class=""><strong>{{$designs}}</strong></h4>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="row justify-content-center">
        <div class="col-md-5 pl-5 pr-5">
            <div class="card">
                <div class="card-block edit-block">
                    <h6 class="card-title"><strong>TOTAL REQUESTS DESIGN</strong></h6>
                    <div class="material-icon-list-demo demo-inner">
                        <h4 class=""><strong>{{$requested}}</strong></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5 pl-5 pr-5">
            <div class="card">
                <div class="card-block edit-block">
                    <h6 class="card-title"><strong>TOTAL APPROVED ORDERS</strong></h6>
                    <div class="material-icon-list-demo demo-inner">
                        <h4 class=""><strong>{{$approved}}</strong></h4>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection
