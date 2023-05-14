@extends('layouts.main_layout', ['title'=>$title, 'role'=>$role, 'page'=>$page])
@section('content')

<!-- Recent Sales -->

<!-- A MODAL TO UPLOAD PROGRAMMES -->
<div class="modal fade" id="programmeModal" tabindex="-1" aria-labelledby="programmeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="programmeModalLabel">Register {{$title}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('add-programme')}}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($departments->count() == 0)
                    <div class="alert alert-danger border-0">You must add departments first, <small><a href="{{route('admin.departments')}}">Add department</a></small></div>
                    @else
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-12 col-lg-12 my-2">
                            <input type="text" name="code" class="form-control" placeholder="program code e.g. ITS" value="{{old('code')}}" />
                            @error('code')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-12 col-lg-12 my-2">
                            <input type="text" class="form-control" name="name" placeholder="program name e.g. Information communication and systems" value="{{old('name')}}" />
                            @error('name')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-12 col-lg-12 my-2">
                            <select name="dept" id="" class="form-control">
                                <option value="">select department...</option>
                                @foreach($departments as $department)
                                <option value="{{$department->id}}" {{old('faculty')==$department->id ? 'selected' : ''}}>{{$department->dept_code}}</option>
                                @endforeach
                            </select>
                            @error('dept')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>Save</button>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END A MODAL TO UPLOAD A PROGRAMME -->


<div class="col-12">
    <div class="card recent-sales overflow-auto">
        <div class="card-body">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h5 class="card-title">Programme List</h5>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#programmeModal">Add programme</button>
                </div>
            </div>
            <table class="table table-borderless datatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Programme name</th>
                        <th scope="col">Programme code</th>
                        <th scope="col">Department</th>
                        <th scope="col">Saved at</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($programmes->count() == 0)
                    <tr>
                        <td colspan="5" class="bg-light">No available programme</td>
                    </tr>
                    @endif
                    @foreach($programmes as $programme)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{$programme->program_name}}</td>
                        <td>{{$programme->program_code}}</td>
                        <td>{{$programme->departments->dept_code}}</td>
                        <td>{{ \Carbon\Carbon::parse($programme->created_at)->diffForHumans() }}</td>
                        <td><span class="badge bg-success">added</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>
</div><!-- End Recent Sales -->

@endsection
