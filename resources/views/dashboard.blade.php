@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content')
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h2 class="mb-2">Welcome, {{ Auth::user()->name }}!</h2>
                            <p class="mb-0">School Management System Dashboard</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <h4 class="mb-0">{{ ucfirst($userType) }}</h4>
                            <small>User Type</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        @if($userType === 'admin')
            <!-- Admin Dashboard Statistics -->
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center icon-warning">
                                    <i class="tim-icons icon-single-02 text-warning"></i>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="numbers">
                                    <p class="card-category">Total Users</p>
                                    <h4 class="card-title">{{ number_format($totalUser) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center icon-warning">
                                    <i class="tim-icons icon-settings text-info"></i>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="numbers">
                                    <p class="card-category">Admins</p>
                                    <h4 class="card-title">{{ number_format($adminCount) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="col-lg-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center icon-warning">
                                <i class="tim-icons icon-chart-pie-36 text-success"></i>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="numbers">
                                <p class="card-category">Teachers</p>
                                <h4 class="card-title">{{ number_format($teacherCount) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center icon-warning">
                                <i class="tim-icons icon-badge text-primary"></i>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="numbers">
                                <p class="card-category">Students</p>
                                <h4 class="card-title">{{ number_format($studentCount) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center icon-warning">
                                <i class="tim-icons icon-single-02 text-danger"></i>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="numbers">
                                <p class="card-category">Parents</p>
                                <h4 class="card-title">{{ number_format($parentCount) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Teacher Schedule Quick View -->
    @if($userType === 'teacher')
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient-info">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="text-white mb-2">Quick Schedule Overview</h5>
                                <p class="text-white mb-0">View your weekly class schedule and manage your teaching assignments.</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('teacher.schedule') }}" class="btn btn-white btn-sm">
                                    <i class="tim-icons icon-calendar-60"></i> View Full Schedule
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- User Information Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">
                            @if($userType === 'admin')
                                Admin Information
                            @elseif($userType === 'teacher')
                                Teacher Information
                            @elseif($userType === 'student')
                                Student Information
                            @elseif($userType === 'parent')
                                Parent Information
                            @endif
                        </h4>
                        @if($userType === 'teacher')
                            <a href="{{ route('teacher.schedule') }}" class="btn btn-primary btn-sm">
                                <i class="tim-icons icon-calendar-60"></i> View My Schedule
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    @if($userType === 'teacher')
                                        <th>Subject</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                    @elseif($userType === 'student')
                                        <th>Class</th>
                                        <th>Roll Number</th>
                                        <th>Parent</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                    @elseif($userType === 'parent')
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Children</th>
                                    @elseif($userType === 'admin')
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Role</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userData as $user)
                                    <tr>
                                        <td>{{ $user->user->name }}</td>
                                        <td>{{ $user->user->email }}</td>
                                        @if($userType === 'teacher')
                                            <td>{{ $user->subject ?? 'N/A' }}</td>
                                            <td>{{ $user->phone ?? 'N/A' }}</td>
                                            <td>{{ $user->address ?? 'N/A' }}</td>
                                        @elseif($userType === 'student')
                                            <td>{{ $user->class->name ?? 'N/A' }}</td>
                                            <td>{{ $user->roll_number ?? 'N/A' }}</td>
                                            <td>{{ $user->parent->user->name ?? 'N/A' }}</td>
                                            <td>{{ $user->phone ?? 'N/A' }}</td>
                                            <td>{{ $user->address ?? 'N/A' }}</td>
                                        @elseif($userType === 'parent')
                                            <td>{{ $user->phone ?? 'N/A' }}</td>
                                            <td>{{ $user->address ?? 'N/A' }}</td>
                                            <td>
                                                @if($user->students && $user->students->count() > 0)
                                                    {{ $user->students->pluck('user.name')->implode(', ') }}
                                                @else
                                                    No children
                                                @endif
                                            </td>
                                        @elseif($userType === 'admin')
                                            <td>{{ $user->phone ?? 'N/A' }}</td>
                                            <td>{{ $user->address ?? 'N/A' }}</td>
                                            <td>{{ $user->role ?? 'Administrator' }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize any charts or dashboard functionality here
            console.log('Dashboard loaded for user type: {{ $userType }}');
        });
    </script>
@endpush
