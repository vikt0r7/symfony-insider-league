<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
    ->exclude('var')
    ->exclude('vendor');

return (new Config())
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setRules([
        '@Symfony' => true,
        '@PSR12' => true,

        // Очистка и читаемость
        'no_unused_imports' => true,
        'no_empty_comment' => true,
        'no_trailing_whitespace' => true,
        'single_blank_line_at_eof' => true,

        // Типизация
        'declare_strict_types' => true,
        'strict_comparison' => true,
        'strict_param' => true,

        // Массивы
        'array_syntax' => ['syntax' => 'short'],
        'trim_array_spaces' => true,
        'whitespace_after_comma_in_array' => true,

        // PHPDoc
        'phpdoc_trim' => true,
        'phpdoc_order' => true,
        'phpdoc_separation' => false,
        'no_superfluous_phpdoc_tags' => true,

        // Классы и методы
        'ordered_class_elements' => true,
        'return_type_declaration' => ['space_before' => 'none'],

        // Пространства имён
        'blank_lines_before_namespace' => ['min_line_breaks' => 1],
    ]);
