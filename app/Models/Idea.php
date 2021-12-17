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

    /**
     * Get all the feedback.
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Get the idea location.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the idea category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The roles that belong to the user.
     */
    public function images()
    {
        return $this->belongsToMany(File::class, 'image_idea', 'idea_id', 'file_id');
    }
}
