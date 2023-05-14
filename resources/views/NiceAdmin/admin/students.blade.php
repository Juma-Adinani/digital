@extends('layouts.main_layout', ['title'=>$title, 'role'=>$role, 'page'=>$page])
@section('content')

<!-- Recent Sales -->

<!-- A MODAL TO UPLOAD students -->
<div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentModalLabel">Register {{$title}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('add-student')}}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($programmes->count() == 0)
                    <div class="alert alert-danger">You cannot add student for now,&nbsp;<a href="/admin/programmes">add programmes first</a></div>
                    @else
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-4 col-lg-4 my-2">
                            <input type="text" name="firstname" class="form-control" placeholder="firstname" value="{{old('firstname')}}" />
                        </div>
                        @error('firstname')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                        <div class="col-sm-12 col-md-4 col-lg-4 my-2">
                            <input type="text" name="middlename" class="form-control" placeholder="middlename" value="{{old('middlename')}}" />
                        </div>
                        @error('middlename')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                        <div class="col-sm-12 col-md-4 col-lg-4 my-2">
                            <input type="text" name="lastname" class="form-control" placeholder="lastname" value="{{old('lastname')}}" />
                        </div>
                        @error('lastname')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-4 col-lg-4 my-2">
                            <input type="email" class="form-control" name="email" placeholder="Enter email address" value="{{old('email')}}" />
                        </div>
                        @error('email')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                        <div class="col-sm-12 col-md-4 col-lg-4 my-2">
                            <input type="tel" class="form-control" name="phone" placeholder="enter phone number" value="{{old('phone')}}" />
                        </div>
                        @error('phone')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                        <div class="col-sm-12 col-md-4 col-lg-4 my-2">
                            <select name="gender" id="" class="form-control">
                                <option value="">select gender...</option>
                                <option value="MALE" {{old('gender') == 'MALE' ? 'selected':''}}>Male</option>
                                <option value="FEMALE" {{old('gender') == 'FEMALE' ? 'selected':''}}>Female</option>
                            </select>
                            @error('gender')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-6 col-lg-6 my-2">
                            <input type="text" class="form-control" name="reg" placeholder="Enter registration number" value="{{old('reg')}}" />
                        </div>
                        @error('reg')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                        <div class="col-sm-12 col-md-6 col-lg-6 my-2">
                            <select name="program" id="" class="form-control">
                                <option value="">select program...</option>
                                @foreach($programmes as $program)
                                <option value="{{$program->id}}" {{old('program') == $program->id ? 'selected':''}}>{{$program->program_code}}</option>
                                @endforeach
                            </select>
                            @error('program')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-6 col-lg-6 my-2">
                            <select name="level" id="" class="form-control">
                                <option value="">select level...</option>
                                @foreach($levels as $level)
                                <option value="{{$level->id}}" {{old('level') == $level->id ? 'selected':''}}>{{$level->level}}</option>
                                @endforeach
                            </select>
                            @error('level')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6 my-2">
                            <select name="year" id="" class="form-control">
                                <option value="">select year of study...</option>
                                @foreach($years as $year)
                                <option value="{{$year['name']}}" {{old('year') == $year['name'] ? 'selected':''}}>{{$year['name']}}</option>
                                @endforeach
                            </select>
                            @error('year')
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
<!-- END A MODAL TO UPLOAD A student -->

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
                <h5 class="card-title">{{$title}} List</h5>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#studentModal">Add student</button>
                </div>
            </div>
            <table class="table table-borderless datatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Reg number</th>
                        <th scope="col">Fullname</th>
                        <th scope="col">Email</th>
                        <th scope="col">Programme</th>
                        <th scope="col">Level</th>
                        <th scope="col">Year study</th>
                        <th scope="col">Saved at</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($students->count() == 0)
                    <tr>
                        <td colspan="7" class="bg-light">No available student</td>
                    </tr>
                    @endif
                    @foreach($students as $student)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{$student->reg_no}}</td>
                        <td>{{$student->users->firstname}}&nbsp;{{$student->users->middlename}}&nbsp;{{$student->users->lastname}}</td>
                        <td>{{$student->users->email}}</td>
                        <td>{{$student->programmes->program_code}}</td>
                        <td>{{$student->levels->level}}</td>
                        <td>{{$student->year_of_study}}</td>
                        <td>{{ \Carbon\Carbon::parse($student->created_at)->diffForHumans() }}</td>
                        <td><span class="badge bg-success">added</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>
</div><!-- End Recent Sales -->

@endsection
