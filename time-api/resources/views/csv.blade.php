@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">{{ __('Csv data') }}</div>
                <div class="table-responsive">
                    @if(isset($response['usages'] )&& $response['code']===200)
                    <table class="table table-striped ">
                        <thead>
                            <tr>
                                <th scope="col">Id </th>
                                <th scope="col">Day</th>
                                <th scope="col">Time used</th>
                                <th scope="col">Location</th>
                                <th scope="col">User id</th>
                                <th scope="col">Application ID</th>
                                <th scope="col">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($response['usages'] as $usage)
                            <tr scope="row">
                                <td>{{$usage->id}}</td>
                                <td>{{$usage->day}}</td>
                                <td>{{$usage->useTime}}</td>
                                <td>{{$usage->location}}</td>
                                <td>{{$usage->user_id}}</td>
                                <td>{{$usage->application_id}}</td>
                                <td> 
                                <a class="btn btn-primary" href="{{'app/detail/'.$usage->application_id}}">
                                    App details
                                </a>
                            </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $response['usages']->links() }}
                    </div>

                    @else
                    <div class="d-flex justify-content-center text-danger">
                        No data to show
                    </div>
                    @endif
                </div>

            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('csv') }}">
                    @csrf
                    <div class="form-group row">
                        <div class="d-none">
                            <input id="id" type="number" class="form-control" name="id" value="{{ Auth::user()->id }}" autofocus>
                        </div>
                        <label for="picture" class="col-md-4 col-form-label text-md-right">csv data</label>
                        <div class="col-md-6">
                            <input id="csv" type="file" class="form-control" name="csv" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Send') }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>

    </div>
</div>

@endsection