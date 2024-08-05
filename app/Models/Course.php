<?php

namespace App\Models;

use App\Models\User;
use App\Models\Certificates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'course_thumbnail',
        'description',
        'price',
        'category',
        'user_id',
    ];

    public function certificates () {
        return $this->hasMany(Certificates::class);
    }

    public function user () {
        return $this->belongsTo(User::class);
    }
}
