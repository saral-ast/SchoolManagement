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
                            <h4 class="card-title">Quizzes</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('quiz.create') }}" class="btn btn-sm btn-primary">Create Quiz</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table tablesorter">
                        <thead class="text-primary">
                        <tr>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Class</th>
                            <th>Total Questions</th>
                            <th>Total Marks</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($quizzes as $quiz)
                            <tr>
                                <td>{{ $quiz->name }}</td>
                                <td>{{ optional($quiz->subject)->name }}</td>
                                <td>{{ optional($quiz->class)->name }}</td>
                                <td>{{ $quiz->total_questions }}</td>
                                <td>{{ $quiz->total_marks }}</td>
                                <td>
                                    <span class="badge {{ $quiz->status === 'done' ? 'badge-success' : 'badge-secondary' }} text-uppercase">{{ $quiz->status }}</span>
                                </td>
                                <td>
                                    @if($quiz->status === 'in_progress')
                                        <a href="{{ route('quiz.selectQuestion', $quiz) }}" class="btn btn-sm btn-info">Select Questions</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No quizzes found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


