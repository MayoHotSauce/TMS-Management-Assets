<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            // First, backup existing data
            $existingData = DB::table('asset_requests')->select('id', 'status')->get();
            
            // Drop the old status column
            Schema::table('asset_requests', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            
            // Create new status column with all needed values
            Schema::table('asset_requests', function (Blueprint $table) {
                $table->enum('status', [
                    'pending',
                    'approved',    // Keep this as is since it's in use
                    'bukti',
                    'final_approval',
                    'completed',
                    'archived',
                    'declined'     // Keep this as an option
                ])->default('pending')->after('requester_email');
            });
            
            // Restore existing data
            foreach ($existingData as $row) {
                DB::table('asset_requests')
                    ->where('id', $row->id)
                    ->update(['status' => $row->status]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Migration failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_requests', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->enum('status', ['pending', 'approved'])
                  ->default('pending')
                  ->after('requester_email');
        });
    }
};
