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

    // QRコード削除
    protected static function boot()
    {
        parent::boot();
// 使っているのでログのコード消す
        static::deleting(function ($reservation) {
            logger()->info('Deleting reservation ID: ' . $reservation->id);

            if ($reservation->qr_code_path) {
                logger()->info('Attempting to delete QR code file: ' . $reservation->qr_code_path);

                try {
                    Storage::disk('public')->delete($reservation->qr_code_path);
                    logger()->info('QR code file deleted successfully.');
                } catch (\Exception $e) {
                    logger()->error('Failed to delete QR code file: ' . $e->getMessage());
                }
            }
        });
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
