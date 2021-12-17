<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Get all idea.
     */
    public function ideas()
    {
        return $this->hasMany(Location::class);
    }
}
