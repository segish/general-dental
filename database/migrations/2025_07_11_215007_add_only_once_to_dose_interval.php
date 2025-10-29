<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE prescription_details MODIFY COLUMN dose_interval ENUM('Once Daily', 'Twice Daily', 'Three Times Daily', 'Every 6 Hours', 'Every 8 Hours', 'PRN','Stat(Only ones)') DEFAULT 'Once Daily'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE prescription_details MODIFY COLUMN dose_interval ENUM('Once Daily', 'Twice Daily', 'Three Times Daily', 'Every 6 Hours', 'Every 8 Hours', 'PRN') DEFAULT 'Once Daily'");
    }
};
