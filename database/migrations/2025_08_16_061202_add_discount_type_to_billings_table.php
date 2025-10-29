<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->enum('discount_type', ['fixed', 'percent'])
                ->nullable()
                ->after('total_amount');
            $table->decimal('discounted_from_amount', 10, 2)->nullable()->after('discount');
            $table->decimal('discounted_amount', 10, 2)->nullable()->after('discounted_from_amount');
            $table->decimal('total_after_discount', 10, 2)->nullable()->after('discounted_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discounted_from_amount', 'discounted_amount', 'total_after_discount']);
        });
    }
};
