@extends('layouts.app')

@section('content')
    <style>
    /* Dark theme refinements for question form */
    .card .form-control, .card select.form-control {
        background-color: #27293d;
        color: #ffffff;
        border: 1px solid #344675;
    }
    .card .form-control::placeholder { color: #9A9A9A; }
    .option-row, #options-list .list-group-item {
        background-color: #27293d;
        border: 1px solid #344675;
    }
    .option-row .option-input {
        background-color: #27293d;
        color: #ffffff;
        border: 1px solid #344675;
    }
    .correct-selector input[type="radio"], .correct-selector input[type="checkbox"] {
        position: static !important;
        margin: 0 !important;
        opacity: 1 !important;
        visibility: visible !important;
    }
    </style>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">Create Question</h5>
                </div>
                <form method="POST" action="{{ route('question.store') }}" autocomplete="off">
                    <div class="card-body">
                        @csrf

                        @include('alerts.success')
                        @include('alerts.error')

                        <x-form-field label="Subject" name="subject_id" type="select">
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id) class="bg-dark text-white">{{ $subject->name }}</option>
                            @endforeach
                        </x-form-field>

                        <x-form-field label="Class" name="class_id" type="select">
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected(old('class_id') == $class->id) class="bg-dark text-white">{{ $class->name }}</option>
                            @endforeach
                        </x-form-field>

                        <div class="form-group{{ $errors->has('question_text') ? ' has-danger' : '' }}">
                            <label for="question_text">Question Text</label>
                            <textarea name="question_text" id="question_text" class="form-control{{ $errors->has('question_text') ? ' is-invalid' : '' }}" rows="4" required>{{ old('question_text') }}</textarea>
                            @include('alerts.feedback', ['field' => 'question_text'])
                        </div>

                        <x-form-field label="mark" name="mark" type="number" value="{{ old('mark', 1) }}" min="1" step="0.01" required>
                            <small class="form-text text-muted">Enter the mark for this question.</small>
                        </x-form-field>

                        <div class="form-group mt-3">
                            <label>Difficulty Level</label>
                            <select name="difficulty" class="form-control" required>
                                <option value="">Select Difficulty</option>
                                <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <label>Question Type</label>
                            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons" role="group" aria-label="Question type">
                                <label class="btn btn-outline-info {{ old('type','single') === 'single' ? 'active' : '' }}">
                                    <input type="radio" name="type" id="single" value="single" autocomplete="off" {{ old('type','single') === 'single' ? 'checked' : '' }}> Single Option
                                </label>
                                <label class="btn btn-outline-info ms-2 {{ old('type') === 'multiple' ? 'active' : '' }}">
                                    <input type="radio" name="type" id="multiple" value="multiple" autocomplete="off" {{ old('type') === 'multiple' ? 'checked' : '' }}> Multiple Option
                                </label>
                            </div>
                        </div>

                        <div class="form-group mt-3" id="options-group">
                            <label class="form-label">Options</label>
                            <ul id="options-list" class="list-group">
                                @for ($i = 0; $i < 2; $i++) <!-- Minimum 2 options -->
                                <li class="list-group-item input-group mb-2 d-flex align-items-center option-row">
                                    <span class="handle me-2" style="cursor: grab;">&#9776;</span>
                                    <span class="correct-selector me-2"></span>
                                    <input type="text" name="options[]" class="form-control option-input" placeholder="Option {{ $i + 1 }}" required>
                                    <button type="button" class="btn btn-danger btn-sm ms-2 remove-option" disabled>&times;</button>
                                </li>
                                @endfor
                            </ul>
                            <button type="button" id="add-option" class="btn btn-secondary btn-sm mt-2">Add Option</button>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button type="submit" class="btn btn-fill btn-primary">Create Question</button>
                        <a href="{{ url()->previous() }}" class="btn btn-fill btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@push('js')
    <script>
        $(function() {
            const maxOptions = 6;
            const minOptions = 2;

            function refreshCorrectSelectors() {
                const type = $('input[name="type"]:checked').val();
                $('.option-row').each(function(i, row) {
                    const $row = $(row);
                    const $selector = $row.find('.correct-selector');
                    const inputVal = $row.find('.option-input').val().trim();
                    $selector.empty();
                    if (inputVal !== '') {
                        if (type === 'single') {
                            $selector.html(`<input type="radio" name="correct_option[]" value="${inputVal}">`);
                        } else {
                            $selector.html(`<input type="checkbox" name="correct_option[]" value="${inputVal}">`);
                        }
                    }
                });
            }


            function updateRemoveButtons() {
                const rowCount = $('.option-row').length;
                $('.remove-option').prop('disabled', rowCount <= minOptions);
            }

            function updatePlaceholders() {
                $('.option-input').each(function(i, el) {
                    $(el).attr('placeholder', `Option ${i + 1}`);
                });
            }

            $('#add-option').click(function() {
                const rowCount = $('.option-row').length;
                if (rowCount < maxOptions) {
                    const newOption =
                        `<li class=\"list-group-item input-group mb-2 d-flex align-items-center option-row\">
                    <span class="handle me-2" style="cursor: grab;">&#9776;</span>
                    <span class=\"correct-selector me-2\"></span>
                    <input type=\"text\" name=\"options[]\" class=\"form-control option-input\" placeholder=\"Option ${rowCount + 1}\" required>
                    <button type=\"button\" class=\"btn btn-danger btn-sm ms-2 remove-option\">&times;</button>
                </li>`;
                    $('#options-list').append(newOption);
                    updateRemoveButtons();
                    updatePlaceholders();
                    refreshCorrectSelectors();
                } else {
                    alert('Maximum of 6 options allowed.');
                }
            });

            // Remove option functionality
            $(document).on('click', '.remove-option', function() {
                if ($('.option-row').length > minOptions) {
                    $(this).closest('.option-row').remove();
                    updateRemoveButtons();
                    updatePlaceholders();
                    refreshCorrectSelectors();
                }
            });

            // Update selectors on option text/type change
            $(document).on('input', '.option-input', refreshCorrectSelectors);
            $('input[name="type"]').change(refreshCorrectSelectors);

            // Initialize SortableJS for drag & drop
            Sortable.create(document.getElementById('options-list'), {
                handle: '.handle',
                animation: 150,
                onSort: function () {
                    updatePlaceholders();
                    refreshCorrectSelectors();
                }
            });

            // Initialize on page load
            updateRemoveButtons();
            updatePlaceholders();
            refreshCorrectSelectors();
        });
    </script>
@endpush
