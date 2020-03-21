@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">{{ __('Detail App') }}</div>
                <div class="table-responsive">
                    @if(isset($response['total_use']) && isset($response['application'])&& isset($response['all_uses']) && $response['code']===200)
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">App name: <?= $response['application']->name ?></h5>

                            @if($response['total_use'][0]->totalTime >60)
                            <h6 class="card-subtitle mb-2 text-muted">Total use: <?= intval($response['total_use'][0]->totalTime / 60) ?> minutes aprox.</h6>
                            @else
                            <h6 class="card-subtitle mb-2 text-muted">Total use: <?= $response['total_use'][0]->totalTime ?> seconds</h6>
                            @endif
                        </div>
                    </div>

                    <table class="table table-striped ">
                        <thead>
                            <tr>
                                <th scope="col">Day</th>
                                <th scope="col">Total time</th>
                                <th scope="col">Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($response['all_uses'] as $usage)
                            <tr scope="row">
                                <td>{{$usage->day}}</td>
                                @if($usage->totalTime>60)
                                <td><?= intval($usage->totalTime / 60) ?> minutes</td>

                                @else
                                <td>{{$usage->totalTime}} seconds</td>

                                @endif
                                <td>{{$usage->location}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>


                    @else
                    <div class="d-flex justify-content-center text-danger">
                        No data to show
                    </div>
                    @endif
                </div>

            </div>


        </div>

    </div>
</div>

@endsection