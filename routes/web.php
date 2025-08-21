<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentParentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Models\Admin;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function(){
     Route::get('/login',[AuthController::class,'login'])->name('login');
     Route::post('/login',[AuthController::class,'loginAttempt'])->name('loginAttempt');
});

Route::middleware('auth')->group(function(){
    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('home');
    Route::get('/dashboard',[DashboardController::class,'dashboard'])->name('dashboard');
    //auth route
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');

    //profile and password routes
    Route::get('/profile/edit', [ProfileController::class,'profile'])->name('profile.edit');
    Route::put('/profile/update',[ProfileController::class,'update'])->name('profile.update');
    Route::put('/profile/change_password',[ProfileController::class,'change_password'])->name('profile.change_password');


    Route::get('/admin',[AdminController::class,'index'])->name('admin.index')->middleware('permission:view.admins');
    Route::get('/admin/create',[AdminController::class,'create'])->name('admin.create')->middleware('permission:create.admins');
    Route::post('/admin/create',[AdminController::class,'store'])->name('admin.store')->middleware('permission:create.admins');
    Route::get('/admin/edit/{admin}',[AdminController::class,'edit'])->name('admin.edit')->middleware('permission:edit.admins');
    Route::put( '/admin/edit/{admin}',[AdminController::class,'update'])->name('admin.update')->middleware('permission:edit.admins');
    Route::delete('/admin/{admin}',[AdminController::class,'destroy'])->name('admin.destroy')->middleware('permission:delete.admins');


    Route::get('/teachers',[TeacherController::class,'index'])->name('teacher.index')->middleware('permission:view.teachers');
    Route::get('/teachers/create',[TeacherController::class,'create'])->name('teacher.create')->middleware('permission:create.teachers');
    Route::post('/teachers/create',[TeacherController::class,'store'])->name('teacher.store')->middleware('permission:create.teachers');
    // Place static route before dynamic '/teacher/{teacher}' to avoid collision with 'schedule'
    Route::get('/teacher/schedule',[ScheduleController::class,'teacherSchedule'])->name('teacher.schedule');
    Route::get('/teacher/{teacher}',[TeacherController::class,'show'])->name('teacher.show')->middleware('permission:view.teachers');
    Route::get('/teachers/{teacher}/edit',[TeacherController::class,'edit'])->name('teacher.edit')->middleware('permission:edit.teachers');
    Route::put('/teachers/{teacher}/edit',[TeacherController::class,'update'])->name('teacher.update')->middleware('permission:edit.teachers');
    Route::delete('/teachers/{teacher}/delete',[TeacherController::class,'destroy'])->name('teacher.destroy')->middleware('permission:delete.teachers');


    Route::get('/parents',[StudentParentController::class,'index'])->name('parent.index')->middleware('permission:view.parents');
    // Place static routes before dynamic to avoid collisions
    Route::get('/parents/create',[StudentParentController::class,'create'])->name('parent.create')->middleware('permission:create.parents');
    Route::post('/parents/create',[StudentParentController::class,'store'])->name('parent.store')->middleware('permission:create.parents');
    // Show must be unique from edit
    Route::get('/parents/{parent}',[StudentParentController::class,'show'])->name('parent.show')->middleware('permission:view.parents');
    Route::get('/parents/{parent}/edit',[StudentParentController::class,'edit'])->name('parent.edit')->middleware('permission:edit.parents');
    Route::put('/parents/{parent}',[StudentParentController::class,'update'])->name('parent.update')->middleware('permission:edit.parents');
    Route::delete('/parents/{parent}',[StudentParentController::class,'destroy'])->name('parent.destroy')->middleware('permission:delete.parents');

    Route::get('/students',[StudentController::class,'index'])->name('student.index')->middleware('permission:view.students');
    Route::get('/students/create',[StudentController::class,'create'])->name('student.create')->middleware('permission:create.students');
    Route::post('/students/create',[StudentController::class,'store'])->name('student.store')->middleware('permission:create.students');
    Route::get('/students/{student}',[StudentController::class,'show'])->name('student.show')->middleware('permission:view.students');
    Route::get('/students/{student}/edit',[StudentController::class,'edit'])->name('student.edit')->middleware('permission:edit.students');
    Route::put('/students/{student}/edit',[StudentController::class,'update'])->name('student.update')->middleware('permission:edit.students');
    Route::delete('/students/{student}/delete',[StudentController::class,'destroy'])->name('student.destroy')->middleware('permission:delete.students');

    Route::get('/classes/{id}/students',[StudentController::class,'getStudents'])->name('classes.students');
    Route::post('/classes/students/multiple', [StudentController::class, 'getStudentsByMultipleClasses'])
    ->name('classes.students.multiple');



    Route::get('/results',[ResultController::class,'index'])->name('result.index')->middleware('permission:view.results');
    Route::get('/result/create',[ResultController::class,'create'])->name('result.create')->middleware('permission:create.results');
    Route::post('/result/create',[ResultController::class,'store'])->name('result.store')->middleware('permission:create.results');
    Route::get('/result/{result}',[ResultController::class,'show'])->name('result.show')->middleware('permission:view.results');
    Route::get('/result/{result}/edit',[ResultController::class,'edit'])->name('result.edit')->middleware('permission:edit.results');
    Route::put('/result/{result}/edit',[ResultController::class,'update'])->name('result.update')->middleware('permission:edit.results');
    Route::get('/result/{result}/download',[ResultController::class,'downloadPdf'])->name('result.download')->middleware('permission:view.results');

    Route::get('/time-table',[ScheduleController::class,'index'])->name('schedule.index');
    Route::get('/time-table/create',[ScheduleController::class,'create'])->name('schedule.create');
    Route::post('/time-table/create',[ScheduleController::class,'store'])->name('schedule.store');
    Route::get('/time-table/edit',[ScheduleController::class,'edit'])->name('schedule.edit');
    Route::post('/time-table/update',[ScheduleController::class,'update'])->name('schedule.update');
    Route::post('/time-table',[ScheduleController::class,'show'])->name('schedule.index');


    Route::get('/subject/{id}/teahcers',[ScheduleController::class,'getTeachers'])->name('suject.teachers');


    Route::get('/questions',[QuestionController::class,'index'])->name('question.index');
    Route::get('/question/create',[QuestionController::class,'create'])->name('question.create');
    Route::post('/question/create',[QuestionController::class,'store'])->name('question.store');
    Route::get('/question/{question}/edit',[QuestionController::class,'edit'])->name('question.edit');
    Route::put('/question/{question}/edit',[QuestionController::class,'update'])->name('question.update');

    Route::get('/quizzes',[QuizController::class,'index'])->name('quiz.index');
    Route::get('/quiz/create',[QuizController::class,'create'])->name('quiz.create');
    Route::post('/quiz/create',[QuizController::class,'store'])->name('quiz.details');
    Route::get('/quiz/{quiz}/selectQuestion',[QuizController::class,'selectQuestion'])->name('quiz.selectQuestion');
    Route::post('/quiz/{quiz}/attach-questions',[QuizController::class,'attachQuestions'])->name('quiz.attachQuestions');
});

Route::get('/register',function(){
    return view('auth.register');
})->name('register');
