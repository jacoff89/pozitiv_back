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
        Schema::create('additional_service_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('additional_service_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('count');
            $table->foreign('additional_service_id')->references('id')->on('additional_services');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_service_order');
    }
};
