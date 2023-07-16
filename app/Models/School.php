<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function images() {
        return $this->morphMany('App\Models\Image', 'imageable');
    }
}
