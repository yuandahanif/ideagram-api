<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedbacks';
    protected $guarded = ['id'];

    /**
     * Get the idea of this feedback.
     */
    public function idea()
    {
        return $this->belongsTo(Idea::class, 'idea_id');
    }

    /**
     * Get the all donation.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class, 'feedback_id');
    }
}
