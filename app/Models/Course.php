<?php

namespace App\Models;

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
    ];

    public function certificates () {
        return $this->hasMany(Certificates::class);
    }
}
