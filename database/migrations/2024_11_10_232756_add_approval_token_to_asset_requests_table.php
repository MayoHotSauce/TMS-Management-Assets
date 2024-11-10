<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('asset_requests', function (Blueprint $table) {
            $table->string('approval_token')->nullable()->unique();
        });
    }

    public function down()
    {
        Schema::table('asset_requests', function (Blueprint $table) {
            $table->dropColumn('approval_token');
        });
    }
};