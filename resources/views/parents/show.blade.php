@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body text-white">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0 d-flex justify-content-between align-items-center text-white">
                            <h3 class="mb-0">Parent Details</h3>
                        </div>
                        <div class="card-body">
                            @include('alerts.success')
                            @include('alerts.error')

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <h5>Name:</h5>
                                    <p class="form-control text-white">{{ $parent->user->name ?? '-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h5>Email:</h5>
                                    <p class="form-control text-white">{{ $parent->user->email ?? '-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h5>Phone Number:</h5>
                                    <p class="form-control text-white">{{ $parent->user->phone_number ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <h5>Secondary Phone:</h5>
                                    <p class="form-control text-white">{{ $parent->secondary_phone ?? '-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h5>Occupation:</h5>
                                    <p class="form-control text-white">{{ $parent->occupation ?? '-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h5>Relation:</h5>
                                    <p class="form-control text-white text-capitalize">{{ $parent->relation ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <h5>Address:</h5>
                                    <p class="form-control text-white">{{ $parent->user->address ?? '-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h5>Birth Date:</h5>
                                    <p class="form-control text-white">{{ isset($parent->user->birth_date) ? \Illuminate\Support\Carbon::parse($parent->user->birth_date)->format('d M, Y') : '-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h5>Gender:</h5>
                                    <p class="form-control text-white text-capitalize">{{ $parent->user->gender ?? '-' }}</p>
                                </div>
                            </div>

                            <h4 class="text-white mb-3">Associated Students</h4>
                            <div class="table-responsive">
                                <table class="table tablesorter text-white">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>Student</th>
                                            <th>Class</th>
                                            <th>Admission No.</th>
                                            <th>Roll No.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($parent->students as $student)
                                            <tr>
                                                <td>{{ $student->user->name ?? '-' }}</td>
                                                <td>{{ $student->class->name ?? '-' }}</td>
                                                <td>{{ $student->admission_number ?? '-' }}</td>
                                                <td>{{ $student->roll_number ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No students assigned.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('parent.index') }}" class="btn btn-sm btn-secondary">Back</a>
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


