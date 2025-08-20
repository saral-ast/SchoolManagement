<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoleAndPermission;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'birth_date',
        'gender',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function admin(){
        return $this->hasOne(Admin::class);
    }

    public function teacher(){
        return $this->hasOne(Teacher::class);
    }

    public function student_parent(){
        return $this->hasOne(StudentParent::class);
    }

      public function student(){
        return $this->hasOne(Student::class);
    }

    public function quizzes(){
        return $this->hasMany(Quiz::class);
    }

    public function user_type()
    {
        // dd($this->admin());
        if ($this->admin()->exists()) {
            return 'admin';
        }

        if ($this->teacher()->exists()) {
            return 'teacher';
        }

        if ($this->student_parent()->exists()) {
            return 'student_parent';
        }

        if ($this->student()->exists()) {
            return 'student';
        }

    return null; // or 'unknown'
}

}
