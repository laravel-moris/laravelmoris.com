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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->string('type')->nullable();
            $table->nullableMorphs('location');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });

        Schema::create('physical_locations', function (Blueprint $table) {
            $table->id();
            $table->string('venue_name')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('directions_url')->nullable();
            $table->timestamps();
        });

        Schema::create('online_locations', function (Blueprint $table) {
            $table->id();
            $table->string('platform')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });

        Schema::create('papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeDelete();
            $table->foreignId('event_id')->constrained()->cascadeDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['user_id', 'event_id']);
        });

        Schema::create('event_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeDelete();
            $table->foreignId('event_id')->constrained()->cascadeDelete();
            $table->string('status')->default('maybe');
            $table->timestamps();
            $table->unique(['user_id', 'event_id']);
        });

        Schema::create('event_sponsor', function (Blueprint $table) {
            $table->foreignId('event_id')->constrained()->cascadeDelete();
            $table->foreignId('sponsor_id')->constrained()->cascadeDelete();
            $table->primary(['event_id', 'sponsor_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_sponsor');
        Schema::dropIfExists('event_user');
        Schema::dropIfExists('papers');
        Schema::dropIfExists('online_locations');
        Schema::dropIfExists('physical_locations');
        Schema::dropIfExists('sponsors');
        Schema::dropIfExists('events');
    }
};
