@extends('layouts.app')

@section('content')
    <x-profileform name="Create Admin" :action="route('admin.store')" :cancel="route('admin.index')" >
        <x-form-field label="Password" name="password" type="password" value="{{ old('password', '') }}" />
        <x-form-field label="Password Confirmation" name="password_confirmation" type="password" value="{{ old('password_confirmation', '') }}" />
    </x-profileform>
@endsection
