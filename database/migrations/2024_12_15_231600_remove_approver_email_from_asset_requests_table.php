<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveApproverEmailFromAssetRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('asset_requests', function (Blueprint $table) {
            $table->dropColumn('approver_email');
        });
    }

    public function down()
    {
        Schema::table('asset_requests', function (Blueprint $table) {
            $table->string('approver_email')->after('requester_email');
        });
    }
}