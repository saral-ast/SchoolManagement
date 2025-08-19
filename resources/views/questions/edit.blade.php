@extends('layouts.app')

@section('content')
    <style>
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
                    <h5 class="title">Edit Question</h5>
                </div>
                <form method="POST" action="{{ route('question.update', $question->id) }}" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="card-body">

                        @include('alerts.success')
                        @include('alerts.error')

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Subject</label>
                                <input type="text" class="form-control" value="{{ optional($question->subject)->name }}" disabled>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Class</label>
                                <input type="text" class="form-control" value="{{ optional($question->class)->name }}" disabled>
                            </div>
                        </div>
                        <input type="hidden" name="subject_id" value="{{ $question->subject_id }}">
                        <input type="hidden" name="class_id" value="{{ $question->class_id }}">

                        <div class="form-group">
                            <label for="question_text">Question Text</label>
                            <textarea name="question_text" id="question_text" class="form-control" rows="4" required>{{ old('question_text', $question->question_text) }}</textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="mark">Mark</label>
                                <input type="number" name="mark" id="mark" class="form-control" value="{{ old('mark', $question->mark) }}" min="1" step="0.01" required>
                            </div>
                            <div class="form-group  col-md-6">
                                <label>Difficulty Level</label>
                                <select name="difficulty" class="form-control" required>
                                    <option value="easy" {{ old('difficulty', $question->difficulty) == 'easy' ? 'selected' : '' }} class= "bg-dark">Easy</option>
                                    <option value="medium" {{ old('difficulty', $question->difficulty) == 'medium' ? 'selected' : '' }} class= "bg-dark">Medium</option>
                                    <option value="hard" {{ old('difficulty', $question->difficulty) == 'hard' ? 'selected' : '' }} class= "bg-dark">Hard</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label>Question Type</label>
                            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons" role="group" aria-label="Question type">
                                <label class="btn btn-outline-info {{ old('type', $question->type) === 'single' ? 'active' : '' }}">
                                    <input type="radio" name="type" id="single" value="single" autocomplete="off" {{ old('type', $question->type) === 'single' ? 'checked' : '' }}> Single Option
                                </label>
                                <label class="btn btn-outline-info ms-2 {{ old('type', $question->type) === 'multiple' ? 'active' : '' }}">
                                    <input type="radio" name="type" id="multiple" value="multiple" autocomplete="off" {{ old('type', $question->type) === 'multiple' ? 'checked' : '' }}> Multiple Option
                                </label>
                            </div>
                        </div>

                        @php
                            $opts = $question->options ?? [];
                            $allOptions = old('options', $opts['options'] ?? []);
                            $correctArray = collect(old('correct_option', $opts['correct_option'] ?? []))->values()->all();
                        @endphp

                        <div class="form-group mt-3" id="options-group">
                            <label class="form-label">Options</label>
                            <ul id="options-list" class="list-group">
                                @forelse($allOptions as $i => $opt)
                                <li class="list-group-item input-group mb-2 d-flex align-items-center option-row">
                                    <span class="handle me-2" style="cursor: grab;">&#9776;</span>
                                    <span class="correct-selector me-2"></span>
                                    <input type="text" name="options[]" class="form-control option-input" value="{{ $opt }}" placeholder="Option {{ $i + 1 }}" required>
                                    <button type="button" class="btn btn-danger btn-sm ms-2 remove-option">&times;</button>
                                </li>
                                @empty
                                @for ($i = 0; $i < 2; $i++)
                                <li class="list-group-item input-group mb-2 d-flex align-items-center option-row">
                                    <span class="handle me-2" style="cursor: grab;">&#9776;</span>
                                    <span class="correct-selector me-2"></span>
                                    <input type="text" name="options[]" class="form-control option-input" placeholder="Option {{ $i + 1 }}" required>
                                    <button type="button" class="btn btn-danger btn-sm ms-2 remove-option" {{ $i < 2 ? 'disabled' : '' }}>&times;</button>
                                </li>
                                @endfor
                                @endforelse
                            </ul>
                            <button type="button" id="add-option" class="btn btn-secondary btn-sm mt-2">Add Option</button>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button type="submit" class="btn btn-fill btn-primary">Update Question</button>
                        <a href="{{ route('question.index') }}" class="btn btn-fill btn-secondary">Back</a>
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
            const initialCorrect = @json($correctArray);

            function refreshCorrectSelectors() {
                const type = $('input[name="type"]:checked').val();
                $('.option-row').each(function(i, row) {
                    const $row = $(row);
                    const $selector = $row.find('.correct-selector');
                    const inputVal = $row.find('.option-input').val().trim();
                    const wasChecked = $selector.find('input').is(':checked');
                    const shouldCheck = wasChecked || initialCorrect.includes(inputVal);
                    $selector.empty();
                    if (inputVal !== '') {
                        if (type === 'single') {
                            $selector.html(`<input type="radio" name="correct_option[]" value="${inputVal}">`);
                        } else {
                            $selector.html(`<input type="checkbox" name="correct_option[]" value="${inputVal}">`);
                        }
                        if (shouldCheck) {
                            $selector.find('input').prop('checked', true);
                        }
                    }
                });
            }

            function updateRemoveButtons() {
                const rowCount = $('.option-row').length;
                $('.remove-option').each(function(index, btn){
                    $(btn).prop('disabled', rowCount <= minOptions);
                });
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
                        `<li class="list-group-item input-group mb-2 d-flex align-items-center option-row">
                            <span class="handle me-2" style="cursor: grab;">&#9776;</span>
                            <span class="correct-selector me-2"></span>
                            <input type="text" name="options[]" class="form-control option-input" placeholder="Option ${rowCount + 1}" required>
                            <button type="button" class="btn btn-danger btn-sm ms-2 remove-option">&times;</button>
                        </li>`;
                    $('#options-list').append(newOption);
                    updateRemoveButtons();
                    updatePlaceholders();
                    refreshCorrectSelectors();
                } else {
                    alert('Maximum of 6 options allowed.');
                }
            });

            $(document).on('click', '.remove-option', function() {
                if ($('.option-row').length > minOptions) {
                    $(this).closest('.option-row').remove();
                    updateRemoveButtons();
                    updatePlaceholders();
                    refreshCorrectSelectors();
                }
            });

            $(document).on('input', '.option-input', refreshCorrectSelectors);
            $('input[name="type"]').change(refreshCorrectSelectors);

            Sortable.create(document.getElementById('options-list'), {
                handle: '.handle',
                animation: 150,
                onSort: function () {
                    updatePlaceholders();
                    refreshCorrectSelectors();
                }
            });

            updateRemoveButtons();
            updatePlaceholders();
            refreshCorrectSelectors();
        });
    </script>
@endpush

