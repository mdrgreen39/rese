<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class);
    }
}
