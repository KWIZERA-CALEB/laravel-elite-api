<?php

namespace App\Models;

use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certificates extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'student_id',
        'cert_code',
    ];

    public function course () {
        return $this->belongsTo(Course::class);
    }

    public function user () {
        return $this->belongsTo(User::class);
    }
}
