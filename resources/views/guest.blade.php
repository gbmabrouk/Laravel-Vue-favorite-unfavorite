@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
                @if ($day === "SA" || $day === "SU")
                    <h1 class="text-center font-weight-bold">Top 10 Users</h1>
                    <div class="top-list-width">
                        <ul class="list-group">
                            @foreach ($users as $user)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ucfirst($user->name)}}
                                    <span class="badge badge-primary badge-pill">{{$user->counter}}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <h1 class="text-center font-weight-bold">Favorites of users</h1>
                    <photos-component :guest='true'></photos-component>
                @endif
        </div>
    </div>
</div>
@endsection