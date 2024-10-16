<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Jobs\GenerateQrCode;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($reservation) {
            if ($reservation->qr_code_path) {
                Storage::disk('public')->delete($reservation->qr_code_path);
            }
        });
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isCheckedIn()
    {
        return $this->can_review == 1;
    }

}
