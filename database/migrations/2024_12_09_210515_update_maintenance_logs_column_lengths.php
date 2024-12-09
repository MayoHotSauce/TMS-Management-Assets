<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMaintenanceLogsColumnLengths extends Migration
{
    public function up()
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->string('approval_status', 100)->change();
            $table->string('status', 100)->change();
        });
    }

    public function down()
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->string('approval_status', 255)->change();
            $table->string('status', 255)->change();
        });
    }
}