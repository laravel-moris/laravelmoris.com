<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use RectorLaravel\Rector\Empty_\EmptyToBlankAndFilledFuncRector;
use RectorLaravel\Set\LaravelSetList;
use RectorPest\Rules\ChainExpectCallsRector;
use RectorPest\Set\PestSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/app',
        __DIR__.'/config',
        __DIR__.'/resources',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ]);

    $rectorConfig->cacheDirectory(__DIR__.'/storage/rector');
    $rectorConfig->cacheClass(FileCacheStorage::class);

    $rectorConfig->sets([
        LaravelSetList::LARAVEL_ARRAYACCESS_TO_METHOD_CALL,
        LaravelSetList::LARAVEL_ARRAY_STR_FUNCTION_TO_STATIC_CALL,
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_COLLECTION,
        LaravelSetList::LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER,
        LaravelSetList::LARAVEL_FACTORIES,
        LaravelSetList::LARAVEL_IF_HELPERS,
        PestSetList::PEST_CODE_QUALITY,
    ]);

    $rectorConfig->rules([
        EmptyToBlankAndFilledFuncRector::class,
        ChainExpectCallsRector::class,
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_84);

    $rectorConfig->parallel();
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);
    $rectorConfig->removeUnusedImports();
};
