<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('penjualan')) {
            return;
        }

        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal');
            $table->unsignedBigInteger('total');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
