@extends('layouts.app')

@section('content')

<div class="container">
    @if (isset($responseAnimals['msg']))
    <div class="alert alert-info">
        <?= $responseAnimals['msg'] ?>
    </div>
</div>
@endif
<div class="row justify-content-center">
    <div class="col">
        <div class="card">
            <div class="card-header">{{ __('Csv data') }}</div>
            <div class="table-responsive">
                @if(isset($response)&& $response['code']===200)
                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th scope="col">Id </th>
                            <th scope="col">Name</th>
                            <th scope="col">Type</th>
                            <th scope="col">Breed</th>
                            <th scope="col">Sex</th>
                            <th scope="col">Age</th>
                            <th scope="col">Latitude</th>
                            <th scope="col">Longitude</th>
                            <th scope="col">Description</th>
                            <th scope="col">Picture</th>
                            <th scope="col">Owner</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($response['usages'] as $usage)
                        <tr scope="row">
                            <td>{{$usage->id}}</td>
                            <td>{{$usage->name}}</td>
                            <td>{{$usage->type}}</td>
                            <td>{{$usage->breed}}</td>
                            <td>{{$usage->sex}}</td>
                            <td>{{$usage->age}}</td>
                            <td>{{$usage->latitude}}</td>
                            <td>{{$usage->longitude}}</td>
                            <td>{{$usage->description}}</td>
                            <td>{{$usage->picture}}</td>
                            <td>{{$usage->id_owner}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $response['usage']->links() }}
                </div>
                @else
                <div class="d-flex justify-content-center text-danger">
                    No data to show
                </div>
                @endif
            </div>

        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" action="{{ url('animal/update') }}">
                @csrf
                <div class="form-group row">
                    <label for="picture" class="col-md-4 col-form-label text-md-right">Prefered photo</label>
                    <div class="col-md-6">
                        <input id="picture" type="file" class="form-control" name="picture" required autofocus>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Update') }}
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