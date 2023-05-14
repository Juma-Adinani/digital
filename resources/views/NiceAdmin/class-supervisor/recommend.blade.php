@extends('layouts.main_layout', ['title'=>$title, 'role'=>$role, 'page'=>$page])
@section('content')

<!-- Recent Sales -->

@if(session('success'))
<div class="alert alert-success">{{session('success')}}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{session('error')}}</div>
@endif
<div class="col-12">
    <div class="card recent-sales overflow-auto">
        <div class="card-body">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h5 class="card-title">Student Requests</h5>
            </div>
            <table class="table table-borderless datatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Fullname</th>
                        <th scope="col">Medical reason</th>
                        <th scope="col">Social reason</th>
                        <th scope="col">Attachment</th>
                        <th scope="col">Departure date</th>
                        <th scope="col">Return date</th>
                        <th scope="col">Place visit</th>
                        <th scope="col">Requested at</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($requests) == 0)
                    <tr>
                        <td colspan="5" class="bg-light">No available request</td>
                    </tr>
                    @endif
                    @foreach($requests as $request)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{$request['firstname']}}&nbsp;{{$request['middlename']}}&nbsp;{{$request['lastname']}}</td>
                        <td>{{$request['medical_reason']}}</td>
                        <td>{{$request['social_reason']}}</td>
                        <td><a href="{{route('view-doc',['doc_id'=>$request['reason_id']])}}" target="_blank">{{$request['attachment']}}</a></td>
                        <td>{{$request['departure_date']}}</td>
                        <td>{{$request['return_date']}}</td>
                        <td>{{$request['place_of_visit']}}</td>
                        <td>{{\Carbon\Carbon::parse($request['created_at'])->diffForHumans()}}</td>
                        <td>
                            <button type="button" class="badge bg-primary" data-bs-toggle="modal" data-bs-target="#requestModal{{$request['id']}}">remark</button>
                        </td>
                    </tr>
                    <!-- A MODAL TO UPLOAD requestS -->
                    <div class="modal fade" id="requestModal{{$request['id']}}" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="requestModalLabel">Provide recommendations to {{$request['firstname']}}&nbsp;{{$request['lastname']}}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{route('supervisor-remark')}}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <input class="form-control" name="leaveId" value="{{$request['id']}}" hidden />
                                        <div class="form-group row">
                                            <div class="col-sm-12 col-md-12 col-lg-12 my-2">
                                                <label for="exampleFormControlTextarea1" class="form-label">Remarks</label>
                                                <textarea class="form-control" id="exampleFormControlTextarea1" name="remarks" rows="3">{{old('remarks')}}</textarea>
                                            </div>
                                            @error('remarks')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>Remark</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- END A MODAL TO UPLOAD A request -->
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div><!-- End Recent Sales -->

@endsection
