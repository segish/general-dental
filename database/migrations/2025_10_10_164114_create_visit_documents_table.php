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
        Schema::create('visit_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained('visits')->onDelete('cascade');
            $table->string('document_path');
            $table->string('original_name');
            $table->string('file_type'); // image, pdf, document, etc.
            $table->string('mime_type');
            $table->bigInteger('file_size'); // in bytes
            $table->text('note')->nullable();
            $table->foreignId('uploaded_by')->constrained('admins')->onDelete('cascade');
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
        Schema::dropIfExists('visit_documents');
    }
};
