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
        Storage::disk('public')->deleteDirectory('qr_codes');
        Storage::disk('public')->deleteDirectory('shops');

        Storage::disk('public')->makeDirectory('qr_codes');
        Storage::disk('public')->makeDirectory('shops');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
