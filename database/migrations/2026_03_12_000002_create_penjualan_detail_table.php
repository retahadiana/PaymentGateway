<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('penjualan_detail')) {
            return;
        }

        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualan')->cascadeOnDelete();
            $table->string('id_barang');
            $table->unsignedBigInteger('harga');
            $table->unsignedInteger('jumlah');
            $table->unsignedBigInteger('subtotal');
            $table->timestamps();

            $table->foreign('id_barang')->references('id_barang')->on('barang')->cascadeOnUpdate();
            $table->index('id_barang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan_detail');
    }
};
