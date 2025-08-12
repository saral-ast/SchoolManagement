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
                            <h4 class="card-title">Admins</h4>
                        </div>
                        @permission('create.admins')
                        <div class="col-4 text-right">
                            <a href="{{ route('admin.create') }}" class="btn btn-sm btn-primary">Add admin</a>
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
                                <th>Awddress</th>
                                <th>Birth Date</th>
                                <th>Gender</th>
                                <th>Phone Number</th>
{{--                                <th>Creation Date</th>--}}
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example user row -->
                              @foreach($admins as $admin)
                            <tr>

                                    <td>{{ $admin->user->name }}</td>
                                    <td><a href="mailto:{{ $admin->user->email }}">{{ $admin->user->email }}</a></td>
{{--                                    <td>{{$admin->user->user_type()}}</td>--}}
                                    <td>{{$admin->user->address}}</td>
                                    <td>{{$admin->user->birth_date}}</td>
                                    <td>{{$admin->user->gender}}</td>
                                    <td>{{$admin->user->phone_number}}</td>
{{--                                    <td>{{$admin->user->created_at}}</td>--}}

                                    <td class="text-right">
                                        @if (!((Auth::user()->id === $admin->user->id) || ($admin->user->id === 1 )))
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        {{-- @dd($user->admin->user_id) --}}

                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            @permission('edit.admins')
                                            <a class="dropdown-item" href="{{ route('admin.edit', $admin->id) }}">Edit</a>
                                            @endpermission
                                            @permission('delete.admins')
                                            <form action="{{ route('admin.destroy', $admin->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button class="dropdown-item">delete</button>
                                            </form>
                                            @endpermission
                                        </div>

                                    </div>
                                     @endif
                                </td>
                            </tr>
                              @endforeach
                            <!-- Repeat for other users -->
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $admins->links() }}
        </div>
    </div>
@endsection
