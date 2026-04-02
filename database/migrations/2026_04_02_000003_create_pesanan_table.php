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
        if (Schema::hasTable('pesanan')) {
            return;
        }
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('id_vendor');
            $table->string('nama_customer');
            $table->string('no_pesanan')->unique();
            $table->decimal('total_harga', 12, 2);
            $table->enum('metode_bayar', ['transfer', 'virtual_account', 'cash'])->default('transfer');
            $table->enum('status_pesanan', ['pending', 'confirmed', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->enum('status_bayar', ['belum_bayar', 'waiting_confirmation', 'terbayar', 'failed'])->default('belum_bayar');
            $table->text('catatan')->nullable();
            $table->string('alamat_pengiriman')->nullable();
            $table->timestamp('tanggal_pesan')->useCurrent();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_vendor')->references('id_vendor')->on('vendor')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
