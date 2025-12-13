<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add expiry_date to products
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'expiry_date')) {
                $table->date('expiry_date')->nullable()->after('unit');
            }
        });

        // Add service_id to sale_items
        Schema::table('sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_items', 'service_id')) {
                $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete()->after('product_id');
            }
        });

        // Allow NULL for product_id to support service-only sale items
        try {
            DB::statement('ALTER TABLE `sale_items` MODIFY `product_id` BIGINT UNSIGNED NULL;');
            // Recreate FK if missing
            DB::statement('ALTER TABLE `sale_items` ADD CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE;');
        } catch (\Exception $e) {
            // Skip if the DB driver prevents modification or constraints already exist
        }

        // Add transaction_year and void fields to sales
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'transaction_year')) {
                $table->integer('transaction_year')->nullable()->after('created_at');
            }
            if (!Schema::hasColumn('sales', 'is_void')) {
                $table->boolean('is_void')->default(false)->after('payment_method');
            }
            if (!Schema::hasColumn('sales', 'voided_by')) {
                $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete()->after('is_void');
            }
            if (!Schema::hasColumn('sales', 'voided_at')) {
                $table->timestamp('voided_at')->nullable()->after('voided_by');
            }
            if (!Schema::hasColumn('sales', 'void_reason')) {
                $table->text('void_reason')->nullable()->after('voided_at');
            }
        });

        // Create void_requests table
        if (!Schema::hasTable('void_requests')) {
            Schema::create('void_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sale_id')->constrained()->onDelete('cascade');
                $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
                $table->dateTime('requested_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->text('reason')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->dateTime('approved_at')->nullable();
                $table->timestamps();
            });
        }

        // Create void_logs table for history/audit
        if (!Schema::hasTable('void_logs')) {
            Schema::create('void_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sale_id')->constrained()->onDelete('cascade');
                $table->enum('action', ['requested', 'approved', 'rejected', 'cancelled']);
                $table->foreignId('performed_by')->constrained('users')->onDelete('cascade');
                $table->dateTime('performed_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->text('note')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        // Populate transaction_year for existing sales (use raw statement)
        try {
            DB::statement('UPDATE `sales` SET `transaction_year` = YEAR(`created_at`) WHERE `created_at` IS NOT NULL');
        } catch (\Exception $e) {
            // Ignore if DB not ready or cannot run at migration time
        }
    }

    public function down(): void
    {
        // Drop void_logs and void_requests
        Schema::dropIfExists('void_logs');
        Schema::dropIfExists('void_requests');

        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'void_reason')) $table->dropColumn('void_reason');
            if (Schema::hasColumn('sales', 'voided_at')) $table->dropColumn('voided_at');
            if (Schema::hasColumn('sales', 'voided_by')) {
                $table->dropForeign(['voided_by']);
                $table->dropColumn('voided_by');
            }
            if (Schema::hasColumn('sales', 'is_void')) $table->dropColumn('is_void');
            if (Schema::hasColumn('sales', 'transaction_year')) $table->dropColumn('transaction_year');
        });

        Schema::table('sale_items', function (Blueprint $table) {
            if (Schema::hasColumn('sale_items', 'service_id')) {
                $table->dropForeign(['service_id']);
                $table->dropColumn('service_id');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'expiry_date')) {
                $table->dropColumn('expiry_date');
            }
        });
    }
};
