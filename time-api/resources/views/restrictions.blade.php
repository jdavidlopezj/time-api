@extends('layouts.app')

@section('content')
<div class="container">
    @if (isset($response['error_msg']))


    @foreach($response['error_msg'] as $error)
    <div class="alert alert-info">
        <?= $error ?>
    </div>
    @endforeach
    @endif
</div>
<div class="row justify-content-center">
    <div class="col">
        <div class="card">
            <div class="card-header">{{ __('Restriction data') }}</div>
            <div class="table-responsive">
                @if(isset($response['restrictions'] )&& $response['code']===200)
                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th scope="col">Id </th>
                            <th scope="col">Max_time</th>
                            <th scope="col">Start hour restriction </th>
                            <th scope="col">Finish hour restriction</th>
                            <th scope="col">User id</th>
                            <th scope="col">Application ID</th>
                            <th scope="col">Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($response['restrictions'] as $restriction)
                        <tr scope="row">
                            <td>{{$restriction->id}}</td>
                            <td>{{$restriction->max_time}}</td>
                            <td>{{$restriction->start_hour_restriction}}</td>
                            <td>{{$restriction->finish_hour_restriction}}</td>
                            <td>{{$restriction->user_id}}</td>
                            <td>{{$restriction->application_id}}</td>
                            <td> 
                                <a class="btn btn-primary" href="{{'app/detail/'.$restriction->application_id}}">
                                    App details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $response['restrictions']->links() }}
                </div>

                @else
                <div class="d-flex justify-content-center text-danger">
                    No data to show
                </div>
                @endif
            </div>

        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('restrictions') }}">
                @csrf
                <div class="form-group row">
                    <div class="form-group row">
                        <label for="max_time" class="col-md-4 col-form-label text-md-right">max time</label>
                        <div class="col-md-6">
                            <input id="max_time" type="time" class="form-control" name="max_time" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="start_hour_restriction" class="col-md-4 col-form-label text-md-right">start hour restriction</label>
                        <div class="col-md-6">
                            <input id="start_hour_restriction" type="time" class="form-control" name="start_hour_restriction" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="finish_hour_restriction" class="col-md-4 col-form-label text-md-right">finish hour restriction</label>
                        <div class="col-md-6">
                            <input id="finish_hour_restriction" type="time" class="form-control" name="finish_hour_restriction" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="application_id" class="col-md-4 col-form-label text-md-right">application id</label>
                        <div class="col-md-6">
                            <input id="application_id" type="number" class="form-control" name="application_id" required autofocus>
                        </div>
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
</div>
</div>
@endsection