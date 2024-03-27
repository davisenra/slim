<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
    ])
    ->setFinder(
        (new PhpCsFixer\Finder())
            ->in([
                __DIR__ . '/src',
                __DIR__ . '/config',
                __DIR__ . '/bootstrap',
                __DIR__ . '/public',
                __DIR__ . '/tests',
            ])
    );
