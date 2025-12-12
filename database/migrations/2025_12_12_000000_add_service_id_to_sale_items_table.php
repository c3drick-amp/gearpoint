<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->nullable()->after('product_id');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });

        // Try to alter product_id to be nullable using Schema change (requires doctrine/dbal)
        try {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->unsignedBigInteger('product_id')->nullable()->change();
            });
        } catch (\Exception $e) {
            // If change() isn't available (no doctrine/dbal), attempt raw SQL for common DB drivers
            if (config('database.default') === 'mysql') {
                DB::statement('ALTER TABLE sale_items MODIFY product_id BIGINT UNSIGNED NULL');
            } elseif (config('database.default') === 'pgsql') {
                DB::statement('ALTER TABLE sale_items ALTER COLUMN product_id DROP NOT NULL');
            }
            // For sqlite or other drivers, manual migration or installing doctrine/dbal is required.
        }
    }

    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        });

        try {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->unsignedBigInteger('product_id')->nullable(false)->change();
            });
        } catch (\Exception $e) {
            if (config('database.default') === 'mysql') {
                DB::statement('ALTER TABLE sale_items MODIFY product_id BIGINT UNSIGNED NOT NULL');
            } elseif (config('database.default') === 'pgsql') {
                DB::statement('ALTER TABLE sale_items ALTER COLUMN product_id SET NOT NULL');
            }
        }
    }
};
