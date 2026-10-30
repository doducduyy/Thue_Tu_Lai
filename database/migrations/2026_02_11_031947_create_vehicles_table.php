<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand')->default('Khác');
            $table->string('category')->default('car');
            $table->unsignedSmallInteger('model_year')->nullable();
            $table->string('license_plate')->unique();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->unsignedSmallInteger('seat_count');
            $table->string('transmission');
            $table->string('fuel_type');
            $table->unsignedSmallInteger('engine_capacity_cc')->nullable();
            $table->decimal('fuel_consumption', 5, 2)->nullable();
            $table->unsignedBigInteger('price_per_day');
            $table->string('pickup_location')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
