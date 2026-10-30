<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('segment')->nullable()->after('category');
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->string('pickup_location')->nullable()->after('end_date');
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('pickup_location');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('segment');
        });
    }
};
