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
        Schema::table('vendor', function (Blueprint $table) {
            if (! Schema::hasColumn('vendor', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }

            if (! Schema::hasColumn('vendor', 'alamat')) {
                $table->text('alamat')->nullable()->after('phone');
            }

            if (! Schema::hasColumn('vendor', 'kota')) {
                $table->string('kota')->nullable()->after('alamat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor', function (Blueprint $table) {
            if (Schema::hasColumn('vendor', 'kota')) {
                $table->dropColumn('kota');
            }

            if (Schema::hasColumn('vendor', 'alamat')) {
                $table->dropColumn('alamat');
            }

            if (Schema::hasColumn('vendor', 'phone')) {
                $table->dropColumn('phone');
            }
        });
    }
};
