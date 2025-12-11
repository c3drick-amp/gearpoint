<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('mechanic_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('motorcycle_details'); // e.g., "Honda Wave 125, ABC-1234"
            $table->enum('status', ['pending', 'ongoing', 'completed', 'cancelled'])->default('pending');
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_jobs');
    }
};