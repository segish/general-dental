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
        Schema::create('billing_services', function (Blueprint $table) {
            $table->id();
            $table->string('service_name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('billing_type', ['one-time', 'recurring'])->default('one-time');
            $table->integer('billing_interval_days')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('payment_timing', ['prepaid', 'postpaid'])->default('prepaid');
            $table->foreignId('service_category_id')->constrained('service_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('billing_services');
    }
};
