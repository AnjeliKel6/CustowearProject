<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('trackings', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('order_id'); // Pastikan unsignedBigInteger
        $table->string('status');
        $table->string('location')->nullable();
        $table->timestamps();

        // Foreign key constraint
        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackings');
    }
};
