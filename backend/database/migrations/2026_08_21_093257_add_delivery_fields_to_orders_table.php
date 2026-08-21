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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('delivery_method_id')
                ->nullable()
                ->constrained('delivery_methods')
                ->nullOnDelete()
                ->after('status');
            $table->string('delivery_method_name')->nullable()->after('delivery_method_id');
            $table->decimal('delivery_price', 10, 2)->default(0)->after('delivery_method_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('delivery_method_id');
            $table->dropColumn(['delivery_method_name', 'delivery_price']);
        });
    }
};
