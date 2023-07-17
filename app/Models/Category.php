<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $table = 'category';
    use HasFactory;

    /**
     * Get items por category.
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}