<!-- check each step a request has been reached
if the status from the dean is accepted then there should be an option to print the report -->

@extends('layouts.main_layout', ['title'=>$title, 'role'=>$role, 'page'=>$page])
@section('content')
@if(session('success'))
<div class="alert alert-success border-0">{{session('success')}}</div>
@endif
@if(session('error'))
<div class="alert alert-success border-0">{{session('error')}}</div>
@endif

@foreach($progress as $progres)

<div class="col-md-8">
    <div class="card bg-{{$progres['bg']}}">
        <div class="card-body">
            <h5 class="card-title text-{{$progres['title-color']}}">{{$progres['title']}}</h5>
            <small class="text-{{$progres['subtitle-color']}} text-end">{{$progres['subtitle']}}</small>
        </div>
        <a href="" class="text-end m-3 text-primary link-d">
            {{$progres['report'] ?? ''}}
        </a>
    </div>
</div>

@endforeach

@endsection
