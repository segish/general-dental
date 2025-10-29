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
        Schema::create('pharmacy_inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('inventory_id')->nullable()->constrained('pharmacy_inventory')->onDelete('cascade');
            $table->foreignId('seller_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->foreignId('buyer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->enum('buyer_type', ['walk-in', 'registered', 'prescription'])->default('walk-in');
            $table->enum('action', ['in', 'out']);
            $table->integer('quantity');
            $table->integer('balance_after');
            $table->string('reference')->nullable();
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
        Schema::dropIfExists('pharmacy_inventory_logs');
    }
};
