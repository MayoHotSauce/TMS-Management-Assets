<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompletionFieldsToMaintenanceLogs extends Migration
{
    public function up()
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->datetime('completion_date')->nullable();
            $table->text('actions_taken')->nullable();
            $table->text('results')->nullable();
            $table->text('replaced_parts')->nullable();
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->string('equipment_status')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('additional_notes')->nullable();
            $table->string('technician_name')->nullable();
            $table->string('follow_up_priority')->nullable();
        });
    }

    public function down()
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->dropColumn('completion_date');
            $table->dropColumn('actions_taken');
            $table->dropColumn('results');
            $table->dropColumn('replaced_parts');
            $table->dropColumn('total_cost');
            $table->dropColumn('equipment_status');
            $table->dropColumn('recommendations');
            $table->dropColumn('additional_notes');
            $table->dropColumn('technician_name');
            $table->dropColumn('follow_up_priority');
        });
    }
} 