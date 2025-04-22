<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->name('*.php')
    ->in(__DIR__)
    ->exclude([
        'bin',
        'var',
        'vendor',
    ])
    ->ignoreDotFiles(false)
    ->ignoreVCS(true)
;

return (new Config())
    ->setCacheFile('/tmp/.php-cs-fixer.cache')
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PhpCsFixer' => true,
        '@PSR12' => true,
        'declare_strict_types' => true,
        'php_unit_data_provider_name' => true,
    ])
    ->setUsingCache(true)
;
