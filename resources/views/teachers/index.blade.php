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
                            <h4 class="card-title">Teachers</h4>
                        </div>
                        @permission('create.teachers')
                        <div class="col-4 text-right">
                            <a href="{{ route('teacher.create') }}" class="btn btn-sm btn-primary">Add teacher</a>
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
{{--                                <th>Type</th>--}}
                                <th>Address</th>
                                <th>Birth Date</th>
                                <th>Gender</th>
                                <th>Phone Number</th>
{{--                                <th>Creation Date</th>--}}
{{--                                <th>Joining Date</th>   --}}
{{--                                <th>Qualification</th>--}}
{{--                                <th>Subjects</th>--}}
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example user row -->
                              @foreach($teachers as $teacher)
                                  <tr>

                                    <td>{{ $teacher->user->name }}</td>
                                    <td><a href="mailto:{{ $teacher->user->email }}">{{ $teacher->user->email }}</a></td>
{{--                                    <td>{{$teacher->user->user_type()}}</td>--}}
                                    <td>{{$teacher->user->address}}</td>
                                    <td>{{$teacher->user->birth_date}}</td>
                                    <td>{{$teacher->user->gender}}</td>
                                    <td>{{$teacher->user->phone_number}}</td>
{{--                                    <td>{{$teacher->user->created_at}}</td>--}}
{{--                                    <td>{{$teacher->joining_date}}</td>--}}
{{--                                    <td>{{$teacher->qualification}}</td>--}}
{{--                                    <td>--}}
{{--                                        @foreach ($teacher->subjects as $subject)--}}
{{--                                          {{ $subject->name }} |--}}
{{--                                        @endforeach--}}
{{--                                    </td>--}}

                                    <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{route('teacher.show',$teacher->id)}}"><i class="tim-icons icon-bullet-list-67"></i>View Details</a>
                                            @permission('edit.teachers')
                                            <a class="dropdown-item" href="{{ route('teacher.edit', $teacher->id) }}"> <i class="tim-icons icon-pencil"></i>Edit</a>
                                            @endpermission
                                            @permission('delete.teachers')
                                            <form action="{{ route('teacher.destroy', $teacher->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button class="dropdown-item"><i class="tim-icons icon-simple-remove"></i>delete</button>
                                            </form>
                                            @endpermission
                                        </div>
                                    </div>
                                </td>
                            </tr>
                              @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
           {{ $teachers->links() }}
        </div>
    </div>
@endsection
