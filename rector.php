<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector;
use Rector\Strict\Rector\Ternary\DisallowedShortTernaryRuleFixerRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap/app.php',
        __DIR__ . '/database',
        __DIR__ . '/public',
    ])
    ->withSkip([
        PrivatizeFinalClassMethodRector::class => [
            __DIR__ . '/app/Models/PanAnalytics.php',
            __DIR__ . '/app/Models/Lease.php',
            __DIR__ . '/app/Filament/Resources/InvoiceResource/Widgets/InvoiceStats.php',
        ],
        DisallowedShortTernaryRuleFixerRector::class => [
            __DIR__ . '/app/Filament/Support/Concerns/Ownership.php',
        ],
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        privatization: true,
        earlyReturn: true,
        strictBooleans: true,
    )
    ->withPhpSets();
