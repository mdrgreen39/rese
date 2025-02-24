<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (app()->environment('local')) {
            Storage::disk('public')->deleteDirectory('qr_codes');
            Storage::disk('public')->deleteDirectory('shops');
            Storage::disk('public')->deleteDirectory('comments');

            Storage::disk('public')->makeDirectory('qr_codes');
            Storage::disk('public')->makeDirectory('shops');
            Storage::disk('public')->makeDirectory('comments');
        }


        if (app()->environment('production')) {

            Storage::disk('s3')->deleteDirectory('shops');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
