@extends('layouts.app')

@section('content')
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No questions found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($questions, 'links'))
                {{ $questions->links() }}
            @endif
        </div>
    </div>
@endsection

@extends('layouts.app')


