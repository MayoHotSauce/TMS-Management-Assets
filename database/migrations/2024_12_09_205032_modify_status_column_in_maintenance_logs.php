<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyApprovalStatusColumnInMaintenanceLogs extends Migration
{
    public function up()
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->string('approval_status', 50)->change(); // Increase column length to 50
            $table->string('status', 50)->change(); // Also increase status column length
        });
    }

    public function down()
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->string('approval_status')->change(); // Revert to default length
            $table->string('status')->change(); // Revert to default length
        });
    }
}