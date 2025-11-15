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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('plate', 15)->unique();
            $table->string('brand', 80)->nullable();
            $table->string('model', 80)->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('cilindraje', 20)->nullable();
            $table->string('vin', 30)->nullable()->unique();
            $table->string('motor', 30)->nullable();
            $table->unsignedInteger('kilometraje')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};

