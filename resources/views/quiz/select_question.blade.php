@extends('layouts.app')

@section('content')
    <style>
        .question-options {
            background-color: rgb(33, 35, 55);
            border: 1px solid #344675;
            border-radius: .25rem;
        }
        .question-options .list-group-item {
            background-color: rgb(48, 50, 74);
            color: #ffffff;
            border: 1px solid #344675;
        }
        .question-options .list-group {
            background-color: transparent;
        }
        .question-options .answer-summary {
            color: #ffffff;
        }
    </style>

    @include('alerts.success')
    @include('alerts.error')

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Select Questions for: {{ $quiz->name }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3 p-3 border rounded" style="border-color:#344675 !important;">
                        <div class="d-flex flex-wrap">
                            <div class="mr-4 mb-2">
                                <strong>Required Questions:</strong> <span id="req-questions">{{ (int) $quiz->total_questions }}</span>
                            </div>
                            <div class="mr-4 mb-2">
                                <strong>Selected:</strong> <span id="sel-questions">0</span>
                            </div>
                            <div class="mr-4 mb-2">
                                <strong>Remaining:</strong> <span id="rem-questions">{{ (int) $quiz->total_questions }}</span>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap mt-2">
                            <div class="mr-4 mb-2">
                                <strong>Required Marks:</strong> <span id="req-marks">{{ (int) $quiz->total_marks }}</span>
                            </div>
                            <div class="mr-4 mb-2">
                                <strong>Selected Marks:</strong> <span id="sel-marks">0</span>
                            </div>
                            <div class="mr-4 mb-2">
                                <strong>Remaining Marks:</strong> <span id="rem-marks">{{ (int) $quiz->total_marks }}</span>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('quiz.attachQuestions', $quiz) }}" method="POST">
        @csrf
                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

                        <table class="table tablesorter" id="question-table">
                            <thead class="text-primary">
                            <tr>
                                <th style="width: 50px;"><input type="checkbox" id="check_all"></th>
                                <th>Question</th>
                                <th>Subject</th>
                                <th>Class</th>
                                <th>Type</th>
                                <th>Difficulty</th>
                                <th>Mark</th>
                                <th>Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($questions as $question)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="question_ids[]" value="{{ $question->id }}" data-mark="{{ (int) $question->mark }}" class="question-checkbox" {{ in_array($question->id, $selectedQuestionIds ?? []) ? 'checked' : '' }}>
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($question->question_text, 80) }}</td>
                                    <td>{{ optional($question->subject)->name }}</td>
                                    <td>{{ optional($question->class)->name }}</td>
                                    <td class="text-capitalize">{{ $question->type }}</td>
                                    <td class="text-capitalize">{{ $question->difficulty }}</td>
                                    <td>{{ $question->mark }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info toggle-collapse-btn"
                                                type="button"
                                                data-toggle="collapse"
                                                data-target="#q-{{ $question->id }}"
                                                aria-expanded="false"
                                                aria-controls="q-{{ $question->id }}">
                                            Show
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8" class="p-0 border-0">
                                        <div id="q-{{ $question->id }}" class="collapse">
                                            <div class="p-3 question-options">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div>
                                                        <span class="badge badge-secondary text-uppercase">{{ $question->type }}</span>
                                                        <span class="badge badge-info">Mark: {{ $question->mark }}</span>
                                                    </div>
                                                </div>
                                                @php
                                                    $opts = $question->options ?? [];
                                                    $allOptions = $opts['options'] ?? [];
                                                    $correct = collect($opts['correct_option'] ?? []);
                                                @endphp
                                                @if(count($allOptions) > 0)
                                                    <ul class="list-group">
                                                        @foreach($allOptions as $idx => $opt)
                                                            @php
                                                                $isCorrect = $correct->contains($opt);
                                                            @endphp
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span>
                                                                    <strong>{{ chr(65 + $idx) }}.</strong> {{ $opt }}
                                                                </span>
                                                                @if($isCorrect)
                                                                    <span class="badge badge-success">Correct</span>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    <div class="mt-2 answer-summary">
                                                        <strong>Answer{{ $correct->count() > 1 ? 's' : '' }}:</strong>
                                                        {{ $correct->isNotEmpty() ? $correct->join(', ') : '—' }}
                                                    </div>
                                                @else
                                                    <em>No options found for this question.</em>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No questions found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" name="action" value="draft" class="btn btn-secondary mr-2">Save Draft</button>
                            <button type="submit" name="action" value="final" class="btn btn-primary">Create Quiz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkAll = document.getElementById('check_all');
            const checkboxes = document.querySelectorAll('.question-checkbox');
            const table = document.getElementById('question-table');
            const reqQ = parseInt(document.getElementById('req-questions')?.textContent || '0');
            const reqM = parseInt(document.getElementById('req-marks')?.textContent || '0');
            const selQEl = document.getElementById('sel-questions');
            const remQEl = document.getElementById('rem-questions');
            const selMEl = document.getElementById('sel-marks');
            const remMEl = document.getElementById('rem-marks');

            function recalc() {
                let selQ = 0;
                let selM = 0;
                document.querySelectorAll('.question-checkbox').forEach(cb => {
                    if (cb.checked) {
                        selQ += 1;
                        const m = parseInt(cb.getAttribute('data-mark') || '0');
                        selM += isNaN(m) ? 0 : m;
                    }
                });
                if (selQEl) selQEl.textContent = String(selQ);
                if (remQEl) remQEl.textContent = String(Math.max(reqQ - selQ, 0));
                if (selMEl) selMEl.textContent = String(selM);
                if (remMEl) remMEl.textContent = String(Math.max(reqM - selM, 0));
            }

            if (checkAll) {
                checkAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => cb.checked = checkAll.checked);
                    recalc();
                });
            }

            // Ensure collapse toggles both open and close on repeated clicks
            if (table) {
                table.addEventListener('click', function (e) {
                    const btn = e.target.closest('.toggle-collapse-btn');
                    if (!btn) return;
                    const targetSelector = btn.getAttribute('data-target');
                    if (!targetSelector) return;
                    const panel = document.querySelector(targetSelector);
                    if (!panel) return;

                    // If using Bootstrap's collapse, toggle via jQuery if available
                    if (window.$ && $(panel).collapse) {
                        $(panel).collapse('toggle');
                    } else {
                        // Fallback: toggle 'show' class manually
                        panel.classList.toggle('show');
                    }
                });
            }

            // Per-checkbox recalc
            document.querySelectorAll('.question-checkbox').forEach(cb => {
                cb.addEventListener('change', recalc);
            });

            // Initial calc for pre-checked values
            recalc();
        });
    </script>
@endpush

