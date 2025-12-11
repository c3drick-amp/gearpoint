<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Oil Change, Tune-up
            $table->string('code')->unique()->nullable();
            $table->string('category')->nullable(); // Maintenance, Repair, etc.
            $table->text('description')->nullable();
            $table->decimal('labor_fee', 10, 2);
            $table->string('estimated_duration')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
