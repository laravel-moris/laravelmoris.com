<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
        });

        DB::table('events')->orderBy('id')->chunk(100, function ($events) {
            foreach ($events as $event) {
                $slug = Str::slug($event->title);
                $baseSlug = $slug;
                $counter = 1;

                while (DB::table('events')->where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                    $slug = $baseSlug.'-'.$counter++;
                }

                DB::table('events')->where('id', $event->id)->update(['slug' => $slug]);
            }
        });

        Schema::table('events', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
