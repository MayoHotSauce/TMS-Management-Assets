<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProofColumnsToAssetRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_requests', function (Blueprint $table) {
            $table->string('proof_image')->nullable();
            $table->text('proof_description')->nullable();
            $table->decimal('final_cost', 15, 2)->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_requests', function (Blueprint $table) {
            $table->dropColumn([
                'proof_image',
                'proof_description',
                'final_cost',
                'completed_at',
                'rejected_at',
                'rejection_notes'
            ]);
        });
    }
}
