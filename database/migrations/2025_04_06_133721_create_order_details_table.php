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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->foreignId('inventory_id')->nullable()->constrained('pharmacy_inventory')->onDelete('set null');
            $table->decimal('price', 24, 2)->default(0.00);
            $table->decimal('discount_on_product', 24, 2)->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('tax_amount', 24, 2)->default(1.00);
            $table->string('unit')->default('pc');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
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
        Schema::dropIfExists('order_details');
    }
};
