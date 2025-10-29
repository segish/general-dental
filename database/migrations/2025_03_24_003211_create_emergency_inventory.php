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
        Schema::create('emergency_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emergency_medicine_id')->constrained('emergency_medicines')->onDelete('cascade');
            $table->string('batch_number')->nullable();
            $table->integer('quantity');
            $table->decimal('buying_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->date('expiry_date')->nullable();
            $table->date('received_date')->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
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
        Schema::dropIfExists('emergency_inventory');
    }
};
