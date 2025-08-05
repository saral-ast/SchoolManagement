@extends('layouts.app')

@section('content')
<form action="{{ route('schedule.store') }}" method="POST">
    @csrf

    <x-form-field label="Select Class" name="class" type="select" class="mb-3">
        @foreach ($allClass as $class)
            <option value="{{ $class->id }}" class="bg-dark">{{ $class->name }}</option>
        @endforeach
    </x-form-field>

    <x-form-field label="Start Date" name="start_date" type="date" class="mb-3" />

    <x-form-field label="End Date" name="end_date" type="date" class="mb-3" />

    <div class="table-responsive mb-4">
        <table class="table table-bordered text-white align-middle">
            <thead>
                <tr>
                    <th>Slot name</th>
                    <th>Select Subject</th>
                    <th>Select Teacher</th>
                    <th>Time Period</th>
                </tr>
            </thead>
            <tbody>
                @foreach($totalSlot as $key => $slot)
                <tr>
                    <td>{{ $slot->name }}</td>

                    <td>
                        <x-form-field label="Subject" name="subject[{{ $key }}]" type="select" required>
                            @foreach ($allSubject as $subject)
                                <option value="{{ $subject->id }}" class="bg-dark">{{ $subject->name }}</option>
                            @endforeach
                        </x-form-field>
                    </td>

                    <td>
                        <x-form-field label="Teacher" name="teacher[{{ $key }}]" type="select" required>
                            <option value="" class="bg-dark">Select Teacher</option>
                            @foreach ($allTeachers as $teacher)
                                <option value="{{ $teacher->id }}" class="bg-dark">{{ $teacher->user->name }}</option>
                            @endforeach
                        </x-form-field>
                    </td>

                    <td>
                        {{ $slot->period ?? 'N/A' }}
                        {{-- Hide period input for submission --}}
                        <input type="hidden" name="period[{{ $key }}]" value="{{ $slot->period }}" />
                    </td>

                    {{-- Hidden slot name --}}
                    <input type="hidden" name="slot[{{ $key }}]" value="{{ $slot->name }}" />
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection
