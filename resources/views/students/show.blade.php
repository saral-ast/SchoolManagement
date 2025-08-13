@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body text-white">
                <div class="row">
                    <div class="col">
                        <div class="card shadow">
                            <div class="card-header border-0 d-flex justify-content-between align-items-center text-white">
                                <h3 class="mb-0">Student Details</h3>
                            </div>
                            <div class="card-body">
                                @include('alerts.success')
                                @include('alerts.error')

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <h5>Name:</h5>
                                        <p class="form-control text-white">{{ $student->user->name ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Email:</h5>
                                        <p class="form-control text-white">{{ $student->user->email ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Phone Number:</h5>
                                        <p class="form-control text-white">{{ $student->user->phone_number ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <h5>Class:</h5>
                                        <p class="form-control text-white">{{ $student->class->name ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Roll No:</h5>
                                        <p class="form-control text-white">{{ $student->roll_number ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Adminssion Number:</h5>
                                        <p class="form-control text-white text-capitalize">{{ $student->admission_number ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <h5>Address:</h5>
                                        <p class="form-control text-white">{{ $student->user->address ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Birth Date:</h5>
                                        <p class="form-control text-white">{{ isset($student->user->birth_date) ? \Illuminate\Support\Carbon::parse($student->user->birth_date)->format('d M, Y') : '-' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Gender:</h5>
                                        <p class="form-control text-white text-capitalize">{{ $student->user->gender ?? '-' }}</p>
                                    </div>
                                </div>

                                <h4 class="text-white mb-3">Associated Parents</h4>
                                <div class="table-responsive">
                                    <table class="table tablesorter text-white">
                                        <thead class="text-primary">
                                        <tr>
                                            <th>Name</th>
{{--                                            <th>Class</th>--}}
                                            <th>Address</th>
                                            <th>Gender.</th>
{{--                                            <th>Roll No.</th>--}}
                                            <th>Occupation.</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($student->parents as $parent)
                                            <tr>
                                                <td>{{ $parent->user->name ?? '-' }}</td>
                                                <td>{{ $parent->user->address ?? '-' }}</td>
                                                <td>{{ $parent->user->gender ?? '-' }}</td>
                                                <td>{{ $parent->occupation ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No parent is  assigned.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('student.index') }}" class="btn btn-sm btn-secondary">Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


