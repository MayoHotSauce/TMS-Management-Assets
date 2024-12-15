<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAssetsTableStatusColumn extends Migration
{
    public function up()
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->string('equipment_status')->nullable();
            $table->text('actions_taken')->nullable();
            $table->text('repair_result')->nullable();
            $table->text('replaced_parts')->nullable();
            $table->decimal('repair_cost', 15, 2)->nullable();
            $table->text('follow_up_recommendation')->nullable();
            $table->text('additional_notes')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('technician_name')->nullable();
        });
    }

    public function down()
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->dropColumn([
                'equipment_status',
                'actions_taken',
                'repair_result',
                'replaced_parts',
                'repair_cost',
                'follow_up_recommendation',
                'additional_notes',
                'completion_date',
                'technician_name'
            ]);
        });
    }
}
