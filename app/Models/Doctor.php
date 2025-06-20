<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specialization',
        'description',
        'photo',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
