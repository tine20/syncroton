<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\CodeQuality\Rector as CodeQuality;
use Rector\Set\ValueObject\LevelSetList;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/lib',
    ])
    ->withRules([
        CodeQuality\Class_\CompleteDynamicPropertiesRector::class
    ])
    ->withSets([
        LevelSetList::UP_TO_PHP_85
    ])
    ->withPhpVersion(PhpVersion::PHP_80);