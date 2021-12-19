<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Get the idea of this comment.
     */
    public function idea()
    {
        return $this->belongsTo(Idea::class, 'idea_id');
    }

    /**
     * Get the idea of this user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
