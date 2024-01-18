<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ApplyRole extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'email',
        'company_id',
        'address',
        'role_applied',
        'role_status',
       
    ];
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
