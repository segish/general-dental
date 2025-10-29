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
    public function up()
    {
        Schema::create('emergency_medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->enum('payment_timing', ['prepaid', 'postpaid'])->default('prepaid');
            $table->enum('item_type', ['medication', 'consumable', 'equipment'])->default('medication');
            $table->foreignId('category_id')->nullable()->constrained('medicine_categories')->onDelete('set null');
            $table->integer('low_stock_threshold')->default(5);
            $table->integer('expiry_alert_days')->default(30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emergency_medicines');
    }
};
