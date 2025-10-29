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
        Schema::create('store_inventory_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_inventory_id')->constrained('store_inventory')->onDelete('cascade');
            $table->integer('quantity'); // Positive for addition, negative for damage/loss
            $table->enum('adjustment_type', ['Damage', 'Loss', 'Correction', 'Other']);
            $table->text('reason')->nullable();
            $table->foreignId('requested_by')->nullable()->constrained('admins')->onDelete('set null'); // Staff who requested
            $table->foreignId('approved_by')->nullable()->constrained('admins')->onDelete('set null'); // Admin who approves
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
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
        Schema::dropIfExists('store_inventory_adjustments');
    }
};
