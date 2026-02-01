<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Unguard models (no $fillable needed on models)
        Model::unguard();

        // Strict mode only in production
        Model::shouldBeStrict(app()->isProduction());

        $this->enforceMorphMap();

        // Prohibit destructive database commands in production
        DB::prohibitDestructiveCommands(app()->isProduction());

        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

        // for OAuth provider checks
        Blade::if('provider', function (string $provider): bool {
            return config("services.{$provider}.client_id") !== null;
        });
    }

    private function enforceMorphMap()
    {

        $ns = app()->getNamespace();

        $map = collect(File::allFiles(app_path('Models')))
            ->map(fn ($f) => $ns.Str::of($f->getRealPath())
                ->after(app_path().DIRECTORY_SEPARATOR)
                ->replace(DIRECTORY_SEPARATOR, '\\')
                ->replaceLast('.php', '')
            )
            ->filter(fn ($class) => class_exists($class) && is_subclass_of($class, Model::class))
            ->mapWithKeys(fn ($class) => [Str::kebab(class_basename($class)) => $class])
            ->all();

        // user => User::class
        // snake-case => PascalCase::class
        Relation::enforceMorphMap($map);
    }
}
