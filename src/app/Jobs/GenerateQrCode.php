<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Storage;

class GenerateQrCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reservation;

    /**
     * Create a new job instance.
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $reservation = $this->reservation;

        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_L,
            'version' => 5,
            'moduleValues' => [
                'backgroundColor' => [255, 255, 255],
                'foregroundColor' => [0, 0, 0],
            ],
            'imageBase64' => false,
        ]);

        $qrCodeData = "予約ID: {$reservation->id} | Shop: {$reservation->shop->name}";

        $qrcode = (new QRCode($options))->render($qrCodeData);

        $path = 'qr_codes/' . $reservation->id . '.png';

        Storage::disk('public')->put($path, $qrcode);

        $reservation->qr_code_path = $path;

        $reservation->save();
    }

}
