@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Create Quiz</h1>
        </div>

        <form action="" method="post">
            @csrf
            <div class="form-group">
                <label for="class_id">Class</label>
                <select name="class_id" id="class_id" class="form-control">
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" class= 'bg-dark text-white'>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="subject_id">Subject</label>
                <select name="subject_id" id="subject_id" class="form-control">
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" class= 'bg-dark text-white'>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="question_id">Title of the Quiz</label>
                <input type="text" name="title" id="title" class="form-control">
            </div>
            @if(auth()->user()->user_type() == 'admin')
                <div class="form-group">
                    <lebel>Teacher</lebel>
                    <select name="teacher_id" id="teacher_id" class="form-control">
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" class= 'bg-dark text-white'>{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection