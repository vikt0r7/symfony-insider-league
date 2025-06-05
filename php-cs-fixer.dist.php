<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests');

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setRules([
        '@Symfony' => true,
        '@PSR12' => true,
        'comment_to_phpdoc' => false,
        'phpdoc_no_empty_return' => false,
        'phpdoc_no_package' => true,
        'phpdoc_summary' => false,
        'phpdoc_trim' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'no_superfluous_phpdoc_tags' => true,
        'no_empty_comment' => true,                // ⬅️ удаляет пустые `/** */`
        'single_line_comment_style' => ['comment_types' => ['hash']], // убирает #
        'no_trailing_whitespace' => true,
    ]);
