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
                            <h4 class="card-title">Parents</h4>
                        </div>
                        @permission('create.parents')
                        <div class="col-4 text-right">
                            <a href="{{ route('parent.create') }}" class="btn btn-sm btn-primary">Add parent</a>
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
                                <th>Address</th>
                                <th>Birth Date</th>
                                <th>Gender</th>
                                <th>Phone Number</th>
                                <th>Occupation</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parents as $parent)
                            <tr>
                                <td>{{ $parent->user->name }}</td>
                                <td><a href="mailto:{{ $parent->user->email }}">{{ $parent->user->email }}</a></td>
                                <td>{{$parent->user->address}}</td>
                                <td>{{$parent->user->birth_date}}</td>
                                <td>{{$parent->user->gender}}</td>
                                <td>{{$parent->user->phone_number}}</td>
                                <td>{{$parent->occupation}}</td>

                                <td class="text-right">
{{--                                @permission('edit.parents' || 'delete.parents')--}}
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="{{ route('parent.show',$parent->id) }}"><i class="tim-icons icon-bullet-list-67"></i>View Details</a>
                                        @permission('edit.parents')
                                        <a class="dropdown-item" href="{{ route('parent.edit', $parent->id) }}"><i class="tim-icons icon-pencil"></i>
                                        Edit</a>
                                        @endpermission


                                        @permission('delete.parents')
                                        <form action="{{ route('parent.destroy', $parent->id) }}" method="POST">
                                            @csrf
                                            @method('delete')

                                            <button class="dropdown-item"><i class="tim-icons icon-simple-remove"></i>
                                                delete</button>
                                        </form>
                                        @endpermission
                                    </div>
                                </div>
{{--                                @endpermission--}}
                            </td>
                        </tr>
                          @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
         {{ $parents->links() }}
        </div>
    </div>
@endsection
