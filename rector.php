<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/config',
        __DIR__ . '/lang',
        __DIR__ . '/public',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->withSets([
        \Rector\Set\ValueObject\SetList::DEAD_CODE,
        \Rector\Set\ValueObject\SetList::CODING_STYLE,
        \Rector\Set\ValueObject\SetList::CODE_QUALITY,
        \RectorLaravel\Set\LaravelSetList::LARAVEL_110
    ])
    ->withRules([
        \Rector\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector::class,
        \Rector\CodingStyle\Rector\Stmt\RemoveUselessAliasInUseStatementRector::class,
        \Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector::class,
        \Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector::class,
        \Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector::class,
    ])
    ->withTypeCoverageLevel(0);
