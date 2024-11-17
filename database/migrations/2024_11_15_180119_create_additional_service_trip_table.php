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
        Schema::create('additional_service_trip', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_id');
            $table->unsignedBigInteger('additional_service_id');
            $table->integer('cost');
            $table->integer('min_сost');
            $table->integer('bonuses');
            $table->foreign('trip_id')->references('id')->on('trips');
            $table->foreign('additional_service_id')->references('id')->on('additional_services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_service_trip');
    }
};
