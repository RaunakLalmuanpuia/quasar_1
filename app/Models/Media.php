<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
       'title',
       'about',
       'image',
       'video_id'
    ];

    // public function users(): BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }
}
