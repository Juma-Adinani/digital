@extends('layouts.main_layout', ['title'=>$title, 'role'=>$role, 'page'=>$page])
@section('content')

<!-- Recent Sales -->

<!-- A MODAL TO UPLOAD departmentS -->
<div class="modal fade" id="departmentModal" tabindex="-1" aria-labelledby="departmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="departmentModalLabel">Register {{$title}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('add-department')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-12 col-lg-12 my-2">
                            <input type="text" name="code" class="form-control" placeholder="department code e.g. CSS" value="{{old('code')}}" />
                        </div>
                        @error('code')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-12 col-lg-12 my-2">
                            <input type="text" class="form-control" name="name" placeholder="department name e.g. Computing Science Studies" value="{{old('name')}}" />
                        </div>
                        @error('name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-12 col-lg-12 my-2">
                            <select name="dept" id="" class="form-control">
                                <option value="">select faulty...</option>
                                @foreach($faculties as $faculty)
                                <option value="{{$faculty->id}}" {{old('faculty') == $faculty->id ? 'selected':''}}>{{$faculty->faculty_code}}</option>
                                @endforeach
                            </select>
                            @error('faculty')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END A MODAL TO UPLOAD A department -->

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
                <h5 class="card-title">department List</h5>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#departmentModal">Add department</button>
                </div>
            </div>
            <table class="table table-borderless datatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">department name</th>
                        <th scope="col">department code</th>
                        <th scope="col">Faculty</th>
                        <th scope="col">Saved at</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($departments->count() == 0)
                    <tr>
                        <td colspan="5" class="bg-light">No available department</td>
                    </tr>
                    @endif
                    @foreach($departments as $department)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{$department->dept_code}}</td>
                        <td>{{$department->dept_name}}</td>
                        <td>{{$department->faculties->faculty_code}}</td>
                        <td>{{ \Carbon\Carbon::parse($department->created_at)->diffForHumans() }}</td>
                        <td><span class="badge bg-success">added</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>
</div><!-- End Recent Sales -->

@endsection
