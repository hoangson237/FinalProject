<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // <--- 1. QUAN TRỌNG

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes; // <--- 2. QUAN TRỌNG

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',      // 0:SV, 1:Admin, 2:GV
        'code',      // Mã định danh
        'birthday',
        'gender',
        'phone',
        'address',
        'avatar',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- HELPER METHODS ---
    public function isAdmin() { return $this->role == 1; }
    public function isTeacher() { return $this->role == 2; }
    public function isStudent() { return $this->role == 0; }

    // --- RELATIONSHIPS ---
    public function classesTeaching() {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    public function registrations() {
        return $this->hasMany(Registration::class, 'student_id');
    }
}