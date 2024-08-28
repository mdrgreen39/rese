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

        logger()->info("QR Code Generation started for reservation ID: {$this->reservation->id}");

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

        // QRコードを生成
        $qrcode = (new QRCode($options))->render($qrCodeData);

        // 保存先のパスを設定
        $path = 'qr_codes/' . $reservation->id . '.png';

        // QRコードをストレージに保存
        Storage::disk('public')->put($path, $qrcode);

        // モデルのqr_code属性とqr_code_path属性を更新
        $reservation->qr_code_path = $path;

        $reservation->save();
        logger()->info("QR Code generated and saved for reservation ID: {$reservation->id}");
    
    }

}
