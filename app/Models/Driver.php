<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $guarded = [];
    

    public function user()
    {
        return $this->belongsTo(App\Models\User::class);
    }

    public function trips()
    {
        return $this->hasMany(App\Models\Trip::class);
    }
}
