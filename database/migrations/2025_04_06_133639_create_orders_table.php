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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('patient_id')->nullable()->constrained('patients')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->enum('buyer_type', ['walk-in', 'registered', 'prescription'])->default('walk-in'); 
            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('extra_discount', 10, 2)->default(0);
            $table->decimal('medicine_total_discount', 10, 2)->default(0);
            $table->decimal('total_tax_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->enum('payment_method', ['cash', 'wallet', 'bank_transfer'])->nullable();
            $table->string('transaction_reference')->nullable();
            $table->string('fs_no')->nullable();
            $table->text('note')->nullable();

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
        Schema::dropIfExists('orders');
    }
};
