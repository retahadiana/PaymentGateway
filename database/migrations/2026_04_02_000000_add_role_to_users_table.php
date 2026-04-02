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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['customer', 'vendor'])->default('customer')->after('email');
            $table->unsignedBigInteger('id_vendor')->nullable()->after('type');
            $table->string('phone')->nullable()->after('id_vendor');
            // Add existing columns from migration
            $table->string('id_google')->nullable()->after('phone');
            $table->string('otp')->nullable()->after('id_google');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['type', 'id_vendor', 'phone', 'id_google', 'otp']);
        });
    }
};
