<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'blog_photo');
    }
}
