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
        Schema::create('specimen_laboratory_request_test', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specimen_id')->constrained()->onDelete('cascade');
            $table->foreignId('laboratory_request_test_id')
                ->constrained('laboratory_request_test', 'id')
                ->onDelete('cascade')
                ->index('specimen_lrt_test_fk'); // Shorter foreign key index name
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
        Schema::dropIfExists('specimen_laboratory_request_test');
    }
};
