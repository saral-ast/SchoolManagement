@extends('layouts.app')

@section('content')

    <x-profileform 
        :user="$admin->user"
        method="put"
        :action="route('admin.update', $admin->id)"
        :cancel="route('admin.index')" 
        name="Edit Admin"
    />

@endsection
