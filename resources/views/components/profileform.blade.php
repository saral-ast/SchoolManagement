@props([
    'user' => null,
    'method' => 'post',
    'action' => '#',
    'name' => 'Create Admin',
    'cancel' => '#'
])

<div class="row justify-content-center">
    <div class="col-md-6 offset-md-0">
        <div class="card">
            <div class="card-header">
                <h5 class="title">{{ $name }}</h5>
            </div>
            <form method="post" action="{{ $action }}" autocomplete="off">
                <div class="card-body">
                    @csrf
                    @if(in_array($method, ['put', 'delete']))
                        @method($method)
                    @endif

                    @include('alerts.success')
                    @include('alerts.error')

                    <x-form-field label="Name" name="name" value="{{ old('name', $user->name ?? '') }}" />

                    <x-form-field label="Email address" name="email" type="email" value="{{ old('email', $user->email ?? '') }}" />
                    
                    <x-form-field label="Birth Date" name="birth_date" type="date" value="{{ old('birth_date', $user->birth_date ?? '') }}" />

                    <x-form-field label="Address" name="address" type="text" value="{{ old('address', $user->address ?? '') }}" />

                    <x-form-field label="Gender" name="gender" type="select">
                        <option value="" {{ old('gender', $user->gender ?? '') === '' ? 'selected' : '' }} class="bg-dark text-white">Select gender</option>
                        <option value="male" {{ old('gender', $user->gender ?? '') === 'male' ? 'selected' : '' }} class="bg-dark text-white">Male</option>
                        <option value="female" {{ old('gender', $user->gender ?? '') === 'female' ? 'selected' : '' }} class="bg-dark text-white">Female</option>
                    </x-form-field>

                    <x-form-field label="Phone Number" name="phone_number" type="tel" value="{{ old('phone_number', $user->phone_number ?? '') }}" />

                    {{ $slot }}
                </div>

                <div class="card-footer d-flex justify-content-between">
                    @if(in_array($method, ['put']))
                        <button type="submit" class="btn btn-fill btn-primary">Update</button>
                    @else
                        <button type="submit" class="btn btn-fill btn-primary">Save</button>
                    @endif

                    <a href="{{ $cancel }}" class="btn btn-fill btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
