<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoomIdToAssetRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('asset_requests', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable()->constrained('rooms');
        });
    }

    public function down()
    {
        Schema::table('asset_requests', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropColumn('room_id');
        });
    }
}
