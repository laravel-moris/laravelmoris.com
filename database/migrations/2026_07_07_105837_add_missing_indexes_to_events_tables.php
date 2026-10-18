<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->index('starts_at', 'idx_events_starts_at');
            $table->index('type', 'idx_events_type');
        });

        Schema::table('event_user', function (Blueprint $table) {
            $table->index(['event_id', 'status'], 'idx_event_user_event_status');
        });

        Schema::table('papers', function (Blueprint $table) {
            $table->index(['event_id', 'status'], 'idx_papers_event_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index(['provider', 'provider_id'], 'idx_users_provider_provider_id');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('idx_events_starts_at');
            $table->dropIndex('idx_events_type');
        });

        Schema::table('event_user', function (Blueprint $table) {
            $table->dropIndex('idx_event_user_event_status');
        });

        Schema::table('papers', function (Blueprint $table) {
            $table->dropIndex('idx_papers_event_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_provider_provider_id');
        });
    }
};
