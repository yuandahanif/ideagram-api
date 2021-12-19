<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;
    protected $table = 'donations';
    protected $guarded = ['id'];

    /**
     * Get the feedback of this donation.
     */
    public function feedback()
    {
        return $this->belongsTo(Feedback::class, 'feedback_id');
    }

    /**
     * Get the feedback of this donation.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
