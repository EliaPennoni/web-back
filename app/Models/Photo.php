<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'trip',
        'category'
    ];
    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_photo');
    }

}
