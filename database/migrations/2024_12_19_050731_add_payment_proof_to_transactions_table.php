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
        Schema::table('transactions', function (Blueprint $table) {
            // Menambahkan kolom untuk menyimpan path bukti pembayaran
            $table->string('payment_proof')->nullable();  // Nullable karena tidak semua transaksi menggunakan transfer bank
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Menghapus kolom saat rollback migration
            $table->dropColumn('payment_proof');
        });
    }
};
