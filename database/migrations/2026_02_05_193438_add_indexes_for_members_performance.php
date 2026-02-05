<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Index for ordering by name in members list
            $table->index('name', 'idx_users_name');
        });

        Schema::table('papers', function (Blueprint $table) {
            // Composite index for counting papers by user
            $table->index(['user_id', 'status'], 'idx_papers_user_status');
        });

        Schema::table('event_user', function (Blueprint $table) {
            // Composite index for counting RSVPs by user
            $table->index(['user_id', 'status'], 'idx_rsvps_user_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_name');
        });

        Schema::table('papers', function (Blueprint $table) {
            $table->dropIndex('idx_papers_user_status');
        });

        Schema::table('event_user', function (Blueprint $table) {
            $table->dropIndex('idx_rsvps_user_status');
        });
    }
};
