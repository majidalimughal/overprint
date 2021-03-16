@extends('layouts.admin')
@section('content')
    <div class="row p-5">
        <div class="col-sm-12 mt-3">
            <h3><b>Plans</b></h3>
        </div>

    </div>
    <div class="container">
        <div class="card-deck mb-3 text-center">

            @foreach($plans as $plan)
                <div class="card mb-4 box-shadow">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">{{$plan->name}}</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">${{$plan->price}} <small class="text-muted">/ month</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>10 users included</li>
                            <li>2 GB of storage</li>
                            <li>Email support</li>
                            <li>Help center access</li>
                        </ul>
                        <a @if($shop->plan_id==$plan->id) disabled @endif type="button" href="{{ route('billing', ['plan' => 2]) }}" class="btn btn-lg btn-block btn-primary text-white">@if($shop->plan_id==$plan->id) Current @else Choose @endif</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
