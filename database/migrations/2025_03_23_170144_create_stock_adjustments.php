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
        Schema::create('pharmacy_stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('cascade');
            $table->foreignId('pharmacy_inventory_id')->constrained('pharmacy_inventory')->onDelete('cascade');
            $table->integer('quantity'); // Negative for damaged/loss, positive for manual corrections
            $table->enum('adjustment_type', ['Damage', 'Expiration', 'Correction', 'Other']);
            $table->text('reason')->nullable(); // Optional comment on why adjustment is made
            $table->foreignId('requested_by')->constrained('admins')->onDelete('cascade'); // Pharmacist who requested
            $table->foreignId('approved_by')->nullable()->constrained('admins')->onDelete('cascade'); // Manager/Admin who approves
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending'); // Approval status
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
        Schema::dropIfExists('stock_adjustments');
    }
};
