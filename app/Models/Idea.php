<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Get the owner.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
