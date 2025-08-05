@extends('layouts.app')  <!-- Assuming app.blade.php is at resources/views/layouts/app.blade.php -->

@section('content')
    <!-- The existing users table and content here -->
    @include('alerts.success')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">students</h4>
                        </div>
                        @permission('create.students')
                        <div class="col-4 text-right">
                            <a href="{{ route('student.create') }}" class="btn btn-sm btn-primary">Add Student</a>
                        </div>
                        @endpermission
                    </div>
                </div>
                <div class="card-body">
                    <table class="table tablesorter">
                        <thead class="text-primary">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Address</th>
                                <th>Birth Date</th>
                                <th>Gender</th>
                                <th>Phone Number</th>
                                <th>Creation Date</th>
                                <th>Admission Number</th>
                                <th>Roll Number</th>
                                <th>Class</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example user row -->
                              @foreach($students as $student)
                            <tr>
                                    
                                    <td>{{ $student->user->name }}</td>
                                    <td><a href="mailto:{{ $student->user->email }}">{{ $student->user->email }}</a></td>
                                    <td>{{$student->user->user_type()}}</td>
                                    <td>{{$student->user->address}}</td>
                                    <td>{{$student->user->birth_date}}</td>
                                    <td>{{$student->user->gender}}</td>
                                    <td>{{$student->user->phone_number}}</td>
                                    <td>{{$student->user->created_at}}</td>
                                    <td>{{$student->admission_number}}</td>
                                    <td>{{$student->roll_number}}</td>
                                    <th>{{$student->class->name}}</th>
                                   
                                    <td class="text-right">
                                     @permission('edit.teachers' || 'delete.teachers')
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            @permission('edit.students')
                                            <a class="dropdown-item" href="{{ route('student.edit', $student->id) }}">Edit</a>
                                            @endpermission
                                            @permission('delete.students')
                                            <form action="{{ route('student.destroy', $student->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button class="dropdown-item">delete</button>
                                            </form>
                                            @endpermission
                                        </div>
                                    </div>
                                    @endpermission
                                </td>      
                            </tr>
                              @endforeach
                            <!-- Repeat for other users -->
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $students->links() }}
        </div>
    </div>
@endsection
