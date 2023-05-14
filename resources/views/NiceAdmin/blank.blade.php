@extends('layouts.main_layout', ['title'=>$title, 'role'=>$role, 'page'=>$page])
@section('content')
@if(session('success'))
<div class="alert alert-success border-0">{{session('success')}}</div>
@endif
@if(session('error'))
<div class="alert alert-success border-0">{{session('error')}}</div>
@endif

@foreach($dashboards as $dashboard)
<a href="{{$dashboard['route']}}" class="col-md-4">
    <div class="card bg-{{$dashboard['bg']}}">
        <div class="card-body">
            <h5 class="card-title text-{{$dashboard['text']}}">{{$dashboard['title']}}</h5>
            <p class="lead text-{{$dashboard['text']}} text-end">{{$dashboard['total']}}</p>
        </div>
    </div>
</a>
@endforeach

@endsection
