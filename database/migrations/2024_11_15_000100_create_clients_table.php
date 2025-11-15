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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 150);
            $table->string('document_type', 30)->nullable();
            $table->string('document_number', 50)->unique();
            $table->string('phone', 30)->nullable();
            $table->string('address', 180)->nullable();
            $table->string('driver_name', 150)->nullable();
            $table->string('driver_phone', 30)->nullable();
            $table->string('email', 120)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};

