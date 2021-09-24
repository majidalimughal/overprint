@extends('layouts.main')
@section('content')
    <div class="row page-titles">
        <div class="col-md-12 col-12 mt-5" align="center">
            <h3 class=""><strong>Have A Great Day </strong></h3>
        </div>
    </div>

    <div class="row " >
        <div class="col-md-6 offset-3">
            <div class="accordion w-100" id="accordionExample">
                @foreach ($contacts as $contact)
                <div class="card">
                    <div class="card-header" id="headingOne">
                      <h2 class="mb-0">
                        <a href="javascript:void(0)" class="btn-block bg-white text-primary text-left" type="button" data-toggle="collapse" data-target="#collapseOne{{$contact->id}}" aria-expanded="true" aria-controls="collapseOne">
                          {{$contact->name}}
                        </a>
                      </h2>
                    </div>
                
                    <div id="collapseOne{{$contact->id}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                      <div class="card-body px-4 py-2">
                        <p>{{$contact->message}}</p>
                      </div>
                    </div>
                  </div>
                @endforeach
                
                
              </div>
        </div>
    </div>
    

@endsection
