<?php

declare(strict_types=1);

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Rector\Config\RectorConfig;

return static function (RectorConfig $config): void {
    $config->paths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ]);

    $config->phpVersion(Rector\ValueObject\PhpVersion::PHP_82);

    // Кастомный traverser: удаляет все комментарии
    $config->phpParserDecorator()
        ->addNodeTraverser(static function (NodeTraverser $traverser): void {
            $traverser->addVisitor(new class extends NodeVisitorAbstract {
                public function enterNode(Node $node): void
                {
                    $node->setAttribute('comments', []);
                }
            });
        });
};
