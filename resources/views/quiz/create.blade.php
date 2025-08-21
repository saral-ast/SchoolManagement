@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="mb-4 text-center">Create New Quiz</h2>

                        <form action="{{ route('quiz.details') }}" method="POST" novalidate>
                            @csrf

                            <x-form-field label="Select Class" name="class_id" type="select" id="class_id">
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" @selected(old('class_id') == $class->id) class="bg-dark">{{ $class->name }}</option>
                                @endforeach
                            </x-form-field>

                            <x-form-field label="Select Subject" name="subject_id" type="select" id="subject_id">
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id) class="bg-dark">{{ $subject->name }}</option>
                                @endforeach
                            </x-form-field>

                            <x-form-field label="Quiz Title" name="name" placeholder="Enter quiz title" id="title" />
                            
                            <x-form-field label="Quiz Descripton" name="description" placeholder="Enter quiz Description" id="description" />


                            <x-form-field label="Total Questions" name="total_questions" type="number" placeholder="10" id="total_questions" min="1" />

                            <x-form-field label="Total Marks" name="total_marks" type="number" placeholder="100" id="total_marks" min="1" />

                            <x-form-field label="Quiz Type" name="type" type="select" id="type">
                                <option value="random" @selected(old('type') == 'random') class="bg-dark">Random</option>
                                <option value="mixed" @selected(old('type') == 'mixed') class="bg-dark">Mixed</option>
                            </x-form-field>

                            <div class="form-group mb-3">
                                <div class="d-flex align-items-center border border-default  rounded px-3 py-2">
                                    <input
                                        type="checkbox"
                                        id="negative_marking_enabled"
                                        name="negative_marking_enabled"
                                        value="1"
                                        class="form-check-input m-0 bg-dark"
                                        {{ old('negative_marking_enabled') ? 'checked' : '' }}
                                    />
                                    <label for="negative_marking_enabled" class="mb-0 ml-2 text-white font-weight-normal">
                                        Enable Negative Marking
                                    </label>
                                </div>
                            </div>

                            {{-- Negative Marking Percent --}}
                            <div class="negative-marking-percent-group d-none">
                                <x-form-field label="Negative Marking Percent (%)" name="negative_marking_percent"
                                              type="number" min="0" max="100" step="0.01" placeholder="Enter negative marking percent" id="negative_marking_percent" />
                            </div>

                            <button type="submit" class="btn btn-primary btn-block mt-4">Select Questions</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            function toggleNegativePercent() {
                if ($('#negative_marking_enabled').is(':checked')) {
                    $('.negative-marking-percent-group').removeClass('d-none').addClass('d-block');
                    $('#negative_marking_percent').attr('required', true).prop('disabled', false);
                } else {
                    $('.negative-marking-percent-group').removeClass('d-block').addClass('d-none');
                    $('#negative_marking_percent').removeAttr('required').val('').prop('disabled', true);
                }
            }
            $('#negative_marking_enabled').change(toggleNegativePercent);
            toggleNegativePercent();
        });
    </script>
@endpush
