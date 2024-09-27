<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'efficiency',
        'available_hours',
    ];
    public $timestamps = false;
}