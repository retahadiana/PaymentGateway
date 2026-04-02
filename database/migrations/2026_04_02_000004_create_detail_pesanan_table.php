<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('detail_pesanan')) {
            return;
        }
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('IdDetail_pesanan');
            $table->unsignedBigInteger('id_pesanan');
            $table->unsignedBigInteger('idmenu');
            $table->string('nama_menu');
            $table->decimal('harga', 10, 2);
            $table->integer('jumlah');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
            
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
            $table->foreign('idmenu')->references('idmenu')->on('menu')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
