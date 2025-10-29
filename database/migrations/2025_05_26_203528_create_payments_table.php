<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('billing_id');
            $table->foreign('billing_id')->references('id')->on('billings')->onDelete('cascade');
            $table->decimal('amount_paid', 10, 2);
            $table->string('payment_method');
            $table->string('invoice_no')->nullable();
            $table->string('fn_no')->nullable();
            $table->unsignedBigInteger('received_by_id');

            $table->foreign('received_by_id')->references('id')->on('admins')->onDelete('cascade');
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
        Schema::dropIfExists('payments');
    }
};
