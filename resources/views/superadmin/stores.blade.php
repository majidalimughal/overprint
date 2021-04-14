@extends('layouts.main')


@section('content')
    <form>
        <div class="row page-titles">
            <div class="col-8 align-self-center">
                <h3><strong>Stores</strong></h3>
            </div>
        </div>

        <div class="orders">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Domain</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($stores as $key=>$store)
                                    <tr class="text-center">
                                        <td>{{(($stores->currentPage()-1)*20)+$key+1}}</td>
                                        <td>{{$store->name}}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
@endsection


@section('scripts')

@endsection
