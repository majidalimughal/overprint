@extends('layouts.admin')
@section('content')
    <div class="row page-titles">
        <div class="col-md-12 col-12 mt-5" align="center">
            <h3 class=""><strong>Have A Great Day </strong></h3>
        </div>
    </div>

    <div class="row justify-content-center" >
        <div class="col-md-6 pl-5 pr-5">
            <div class="card">
                <div class="card-block edit-block">
                    <h6 class="card-title"><strong>Total New Orders</strong></h6>
                    <div class="material-icon-list-demo demo-inner">
                        <h4 class=""><strong>{{$new_orders}}</strong></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 pl-5 pr-5">
            <div class="card">
                <div class="card-block edit-block">
                    <h6 class="card-title"><strong>Total Completed Orders</strong></h6>
                    <div class="material-icon-list-demo demo-inner">
                        <h4 class=""><strong>{{$completed_orders}}</strong></h4>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-6 pl-5 pr-5">
            <div class="card">
                <div class="card-block edit-block">
                    <h6 class="card-title"><strong>Total New Orders Today</strong></h6>
                    <div class="material-icon-list-demo demo-inner">
                        <h4 class=""><strong>{{$new_orders_today}}</strong></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 pl-5 pr-5">
            <div class="card">
                <div class="card-block edit-block">
                    <h6 class="card-title"><strong>Total Completed Orders Today</strong></h6>
                    <div class="material-icon-list-demo demo-inner">
                        <h4 class=""><strong>{{$completed_orders_today}}</strong></h4>
                    </div>
                </div>
            </div>
        </div>
        

    </div>
    

@endsection
