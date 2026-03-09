<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/config',
        __DIR__ . '/models',
        __DIR__ . '/api',
    ])
    ->append([
        __DIR__ . '/index.php',
    ]);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PER-CS'                             => true,
        'strict_param'                        => true,
        'declare_strict_types'                => true,
        'array_syntax'                        => ['syntax' => 'short'],
        'no_unused_imports'                   => true,
        'ordered_imports'                     => ['sort_algorithm' => 'alpha'],
        'single_quote'                        => true,
        'trailing_comma_in_multiline'         => true,
        'no_whitespace_before_comma_in_array' => true,
        'trim_array_spaces'                   => true,
        'cast_spaces'                         => ['space' => 'single'],
        'no_extra_blank_lines'                => true,
    ])
    ->setFinder($finder);
