<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalStatusToMaintenanceLogs extends Migration
{
    public function up()
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('maintenance_logs', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            }
        });
    }

    public function down()
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->dropColumn('approval_status');
        });
    }
} 