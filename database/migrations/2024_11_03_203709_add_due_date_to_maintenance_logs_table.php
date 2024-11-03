<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->date('due_date')->nullable(); // Add the due_date column
        });
    }
 
    public function down()
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->dropColumn('due_date'); // Remove the due_date column if needed
        });
    }
};
