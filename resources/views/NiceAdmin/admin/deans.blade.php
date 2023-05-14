@extends('layouts.main_layout', ['title'=>$title, 'role'=>$role, 'page'=>$page])
@section('content')

<!-- Recent Sales -->

<!-- A MODAL TO UPLOAD deanS -->
<div class="modal fade" id="deanModal" tabindex="-1" aria-labelledby="deanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deanModalLabel">Register {{$title}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('add-dean')}}" method="POST">
                @csrf
                <div class="modal-body">
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
                        <div class="col-sm-12 col-md-6 col-lg-6 my-2">
                            <input type="email" class="form-control" name="email" placeholder="Enter email address" value="{{old('email')}}" />
                        </div>
                        @error('email')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                        <div class="col-sm-12 col-md-6 col-lg-6 my-2">
                            <input type="tel" class="form-control" name="phone" placeholder="enter phone number" value="{{old('phone')}}" />
                        </div>
                        @error('phone')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-6 col-lg-6 my-2">
                            <select name="faculty" id="" class="form-control">
                                <option value="">select faulty...</option>
                                @foreach($faculties as $faculty)
                                <option value="{{$faculty->id}}" {{old('faculty') == $faculty->id ? 'selected':''}}>{{$faculty->faculty_code}}</option>
                                @endforeach
                            </select>
                            @error('faculty')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6 my-2">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END A MODAL TO UPLOAD A dean -->

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
                <h5 class="card-title">dean List</h5>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deanModal">Add dean</button>
                </div>
            </div>
            <table class="table table-borderless datatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Fullname</th>
                        <th scope="col">Email</th>
                        <th scope="col">Faculty</th>
                        <th scope="col">Saved at</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($deans->count() == 0)
                    <tr>
                        <td colspan="5" class="bg-light">No available dean</td>
                    </tr>
                    @endif
                    @foreach($deans as $dean)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{$dean->users->firstname}}&nbsp;{{$dean->users->middlename}}&nbsp;{{$dean->users->lastname}}</td>
                        <td>{{$dean->users->email}}</td>
                        <td>{{$dean->faculties->faculty_code}}</td>
                        <td>{{ \Carbon\Carbon::parse($dean->created_at)->diffForHumans() }}</td>
                        <td><span class="badge bg-success">added</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div><!-- End Recent Sales -->

@endsection
