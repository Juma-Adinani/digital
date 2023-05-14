@extends('layouts.main_layout', ['title'=>$title, 'role'=>$role, 'page'=>$page])
@section('content')
@if(session('success'))
<div class="alert alert-success border-0">{{session('success')}}</div>
@endif

@if(session('error'))
<div class="alert alert-danger border-0">{{session('error')}}</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(!empty($permissionExist['reasons']))
<div class="alert alert-danger fw-bolder">Wait for another request to be answered before requesting another one...<br /><a href="{{route('permission-progress')}}">view request permission progress</a></div>
@else
<form method="POST" action="{{route('make-request')}}" enctype="multipart/form-data">
    @csrf
    <div class="col-md-12">
        <div class="card bg-white">
            <div class="card-header bg-light">
                <small class="card-title">1. Select reason(s) for permission</small>
            </div>
            <div class="card-body">
                <div class="form-group row my-3">
                    @foreach($reasons as $reason)
                    <div class="col-md-4">
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="{{$reason->id}}-radio" name="reason" value="{{$reason->id}}" {{old('reason') == $reason->id ? 'checked' : ''}}>
                            <label class="form-check-label" for="{{$reason->id}}-radio">{{$reason->type}}</label>
                        </div>
                    </div>
                    @endforeach
                    @error('reason')
                    <span class="text-danger text-center">{{$message}}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white">
            <div class="card-header bg-light">
                <small class="card-title">2. Fill in particulars</small>
            </div>
            <div class="card-body">
                <div class="form-group row my-3">
                    <div class="col-md-4">
                        @php
                        $studentName = $student->users->firstname . ' ' . $student->users->middlename . ' ' . $student->users->lastname;
                        @endphp
                        <label class="my-1 text-muted">Fullname</label>
                        <input class="form-control" value="{{$studentName}}" disabled />
                    </div>
                    <div class="col-md-4">
                        <label class="my-1 text-muted">Programme</label>
                        <input class="form-control" value="{{$student->program_code}}" disabled />
                    </div>
                    <div class="col-md-4">
                        <label class="my-1 text-muted">Faculty</label>
                        <input class="form-control" value="{{$student->faculty_code}}" disabled />
                    </div>
                </div>
                <div class="form-group row my-3">
                    <div class="col-md-4">
                        <label class="my-1 text-muted">Registration number</label>
                        <input class="form-control" value="{{$student->reg_no}}" disabled />
                    </div>
                    <div class="col-md-4">
                        <label class="my-1 text-muted">Phone number</label>
                        <input class="form-control" value="{{$student->users->phone}}" disabled />
                    </div>
                    <div class="col-md-4">
                        <label class="my-1 text-muted">Education level</label>
                        <input class="form-control" value="{{$student->levels->level}}" disabled />
                    </div>
                </div>
                <div class="form-group row my-3">
                    <div class="col-md-4 my-1">
                        <label for="gender" class="text-muted mb-1">Gender</label>
                        <input class="form-control" value="{{$student->users->gender}}" disabled />
                    </div>
                    <div class="col-md-4 my-1">
                        <label for="marital" class="text-muted mb-1">Marital status</label>
                        <select class="form-control" name="marital" id="marital">
                            <option value="">select marital status...</option>
                            <option value="SINGLE" {{old('marital') == 'SINGLE' ? 'selected' : ''}}>SINGLE</option>
                            <option value="MARRIED" {{old('marital') == 'MARRIED' ? 'selected' : ''}}>MARRIED</option>
                        </select>
                        @error('marital')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 my-1">
                        <label for="place" class="text-muted mb-1">Place to visit</label>
                        <input type="text" class="form-control" name="place" id="place" value="{{old('place')}}" placeholder="enter a place you want to leave to" />
                        @error('place')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row my-3">
                    <div class="col-md-4 my-1">
                        <label for="address" class="text-muted mb-1">Address of place of visit</label>
                        <input type="text" class="form-control" name="address" id="address" value="{{old('address')}}" placeholder="enter address" />
                        @error('address')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 my-1">
                        <label for="departure" class="text-muted mb-1">Departure date</label>
                        <input type="date" class="form-control" name="departure" id="departure" value="{{old('departure')}}" placeholder="enter departure date" />
                        @error('departure')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 my-1">
                        <label for="return" class="text-muted mb-1">Return date</label>
                        <input type="date" class="form-control" name="return" id="return" value="{{old('return')}}" placeholder="enter return date" />
                        @error('return')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white">
            <div class="card-header bg-light">
                <small class="card-title">3. Fill in the reason(s) for leave</small>
            </div>
            <div class="card-body">
                <div class="form-group row my-3" id="medical-reason" style="display:none">
                    <label for="medical" class="text-muted mb-1">Medical reasons</label>
                    <div class="col-md-12">
                        <textarea type="text" name="medical" id="medical" class="form-control" placeholder="fill the medical reasons for leave" cols="30" rows="4">{{old('medical')}}</textarea>
                        @error('medical')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row my-3" id="social-reason" style="display:none">
                    <label for="social" class="text-muted mb-1">Social reasons</label>
                    <div class="col-md-12">
                        <textarea type="text" name="social" id="social" class="form-control" placeholder="fill the social reasons for leave" cols="30" rows="4">{{old('social')}}</textarea>
                        @error('social')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row my-3" id="attachment-doc" style="display:none">
                    <label class="text-muted mb-1">Medical Attachment</label>
                    <div class="col-md-12">
                        <input type="file" class="form-control" name="attachment" id="attachment" />
                        @error('attachment')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white">
            <div class="card-header bg-light">
                <small class="card-title">4. Fill in the session(s) that will be conducted when you are away</small>
            </div>
            <div class="card-body">
                <div class="form-group mt-2" id="session-row">
                    <div id="sessions-container">
                        <div class="session-row row mb-3">
                            <div class="col-md-3">
                                <label for="session" class="text-muted my-1">Session conducted</label>
                                <select class="form-control" id="session" name="session_type[]">
                                    <option value="">select session type...</option>
                                    @foreach($sessions as $session)
                                    <option value="{{$session->id}}">{{$session->type}}</option>
                                    @endforeach
                                </select>
                                @error('session_type')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="subject" class="text-muted my-1">Subject name</label>
                                <input type="text" id="subject" placeholder="subject code e.g. CSS 111" class="form-control" name="subject[]" />
                                @error('subject')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="lecturer" class="text-muted my-1">Lecturer name</label>
                                <input type="text" id="lecturer" placeholder="enter lecturer name" class="form-control" name="lecturer[]" />
                                @error('lecturer')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="commence" class="text-muted my-1">Commenced date</label>
                                <input type="date" id="commence" placeholder="enter a date a session to be carried on" class="form-control" name="commence[]" />
                                @error('commence')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 my-3 mt-5">
                        <button type="button" class="btn btn-primary add-session"><i class="bi bi-plus"></i>&nbsp;Add a session</button>
                    </div>

                    <script>
                        const addSessionButton = document.querySelector('.add-session');
                        const sessionsContainer = document.querySelector('#sessions-container');

                        addSessionButton.addEventListener('click', () => {
                            const sessionRow = sessionsContainer.querySelector('.session-row');
                            const clone = sessionRow.cloneNode(true);

                            // create remove button and label
                            const removeButton = document.createElement('button');
                            removeButton.type = 'button';
                            removeButton.classList.add('btn', 'btn-danger', 'remove-session');
                            removeButton.innerHTML = '<i class="bi bi-trash"></i>&nbsp;Remove';

                            const removeButtonLabel = document.createElement('label');
                            removeButtonLabel.textContent = '';

                            // add event listener to remove button
                            removeButton.addEventListener('click', () => {
                                clone.remove();
                            });

                            // append remove button and label to cloned row
                            const buttonWrapper = document.createElement('div');
                            buttonWrapper.classList.add('col-md-3', 'd-flex', 'flex-column', 'align-items-start', 'justify-content-start', 'my-2');
                            buttonWrapper.appendChild(removeButtonLabel);
                            buttonWrapper.appendChild(removeButton);
                            clone.appendChild(buttonWrapper);

                            // adjust column widths for cloned row
                            const clonedCols = clone.querySelectorAll('.col-md-3');
                            clonedCols.forEach((col) => {
                                col.classList.remove('col-md-3');
                                col.classList.add('col-md-3');
                            });

                            sessionsContainer.appendChild(clone);
                        });
                    </script>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
                        $(document).ready(function() {
                            // Attach click event listener to radio buttons
                            $('input[type=radio][name=reason]').change(function() {
                                // Hide all divs by default
                                $('#medical-reason, #social-reason, #attachment-doc').hide();

                                // Show the appropriate div(s) based on selected radio button
                                if (this.value === "1") {
                                    $('#medical-reason, #attachment-doc').show();
                                } else if (this.value === "2") {
                                    $('#social-reason').show();
                                } else if (this.value === "3") {
                                    $('#medical-reason, #social-reason, #attachment-doc').show();
                                }
                            });
                        });
                    </script>
                </div>
            </div>
            <div class="container d-flex justify-content-end my-3">
                <button type="submit" class="btn btn-success add-session">Send the request</button>
            </div>
        </div>
    </div>
</form>
@endif

@endsection
