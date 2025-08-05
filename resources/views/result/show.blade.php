@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body text-white">
            <div class="row">
                <div class="col">
                    <div class="card  shadow">
                        <div class="card-header border-0 text-white">
                            <h3 class="mb-0">Student Result Overview</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <h5>Class:</h5>
                                    <p class="form-control  text-white">{{ $result->class->name }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h5>Student:</h5>
                                    <p class="form-control  text-white">{{ $result->student->user->name }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h5>Exam Date:</h5>
                                    <p class="form-control  text-white">{{ \Illuminate\Support\Carbon::parse($result->exam_date)->format('d M, Y') }}</p>
                                </div>
                            </div>

                            <h4 class="text-white mb-3">Subject Marks</h4>
                            <div class="table-responsive">
                                <table class="table  table-bordered text-white">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Subject</th>
                                            <th>Total Marks</th>
                                            <th>Obtained Marks</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($result->subjectMarks as $subjectMark)
                                            <tr>
                                                <td>{{ $subjectMark->subject->id }}</td>
                                                <td>{{ $subjectMark->subject->name }}</td>
                                                <td>{{ $subjectMark->total_mark }}</td>
                                                <td>{{ $subjectMark->obtained_mark }}</td>
                                                <td>{{ $subjectMark->grade }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <h4 class="text-white mt-4">Summary</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <h6>Total Marks:</h6>
                                    <p class="form-control  text-white">{{ $result->total_mark }}</p>
                                </div>
                                <div class="col-md-3">
                                    <h6>Obtained Marks:</h6>
                                    <p class="form-control  text-white">{{ $result->obtained_mark }}</p>
                                </div>
                                <div class="col-md-3">
                                    <h6>Overall Grade:</h6>
                                    <p class="form-control  text-white">{{ $result->grade }}</p>
                                </div>
                                <div class="col-md-3">
                                    <h6>Exam Type:</h6>
                                    <p class="form-control  text-white">{{ ucfirst($result->exam_type) }}</p>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <h6>Result Status:</h6>
                                    <p class="form-control  text-white">{{ ucfirst($result->result_status) }}</p>
                                </div>
                            </div>
                                <a href="{{ route('result.index') }}" class="btn btn-fill btn-primary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
@endsection
