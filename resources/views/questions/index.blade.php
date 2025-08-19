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

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Questions</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('question.create') }}" class="btn btn-sm btn-primary">Add Question</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table tablesorter">
                        <thead class="text-primary">
                        <tr>
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
                                <td>{{ \Illuminate\Support\Str::limit($question->question_text, 80) }}</td>
                                <td>{{ optional($question->subject)->name }}</td>
                                <td>{{ optional($question->class)->name }}</td>
                                <td class="text-capitalize">{{ $question->type }}</td>
                                <td class="text-capitalize">{{ $question->difficulty }}</td>
                                <td>{{ $question->mark }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info"
                                            data-toggle="collapse"
                                            data-target="#q-{{ $question->id }}"
                                            aria-expanded="false"
                                            aria-controls="q-{{ $question->id }}">
                                        Show
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" class="p-0 border-0">
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
                                <td colspan="7" class="text-center">No questions found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    @if(method_exists($questions, 'links'))
                        {{ $questions->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
