@extends('layouts.app')


@section('content')
            <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Results</h4>
                        </div>
                        @permission('create.results')
                        <div class="col-4 text-right">
                            <a href="{{ route('result.create') }}" class="btn btn-sm btn-primary">Add Result</a>
                        </div>
                        @endpermission
                    </div>
                </div>
                <div class="card-body">
                    <table class="table tablesorter">
                        <thead class="text-primary">
                            <tr>
                                <th>id</th>
                                <th>Class</th>
                                <th>Student Name</th>
                                <th>Exam Date</th>
                                <th>Exam Type</th>
                                <th>Total Marks</th>
                                <th>Obtained Marks</th>
                                <th>Grade</th>
                                <th>Result Status</th>
                                <th></th>
{{--                                @permission('edit.results')--}}
{{--                                   <th></th>--}}
{{--                                @endpermission--}}

                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example user row -->
                              @foreach($results as $result)
                            <tr>

                                    <td>{{ $result->id }}</td>
                                    <td>{{$result->class->name}}</td>
                                    <td><a href="{{ route('result.show',$result->id) }}">{{ $result->student->user->name }}</a></td>
                                    <td>{{$result->exam_date}}</td>
                                    <td>{{$result->exam_type}}</td>
                                    <td>{{$result->total_mark}}</td>
                                    <td>{{$result->obtained_mark}}</td>
                                    <td>{{$result->grade}}</td>
                                    <td>{{$result->result_status}}</td>

{{--                                     @permission('edit.results')--}}
                                    <td class="text-right">
                                        {{-- @if (!((Auth::user()->id === $admin->user->id) || ($admin->user->id === 1 ))) --}}
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        {{-- @dd($user->admin->user_id) --}}

                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            @permission('edit.results')
                                            <a class="dropdown-item" href="{{ route('result.edit', $result->id) }}"><i class="tim-icons icon-simple-remove"></i>Edit</a>
                                            @endpermission

                                            <a class="dropdown-item" href="{{ route('result.show',$result->id) }}"><i class="tim-icons icon-bullet-list-67"></i>View Details</a>
                                            <a class="dropdown-item" href="{{ route('result.download',$result->id) }}"><i class="tim-icons icon-cloud-download-93"></i>
                                                Download</a>
                                            {{-- @permission('delete.admins')
                                            <form action="{{ route('admin.destroy', $admin->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button class="dropdown-item">delete</button>
                                            </form>
                                            @endpermission --}}
                                        </div>


                                    </div>
                                     {{-- @endif --}}
                                </td>
{{--                                      @endpermission--}}
                            </tr>
                              @endforeach
                            <!-- Repeat for other users -->
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $results->links() }}
        </div>
    </div>

@endsection
