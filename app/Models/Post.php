<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Post extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'title',
        'body',
        'user_id',
    ];

    // Each post belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}