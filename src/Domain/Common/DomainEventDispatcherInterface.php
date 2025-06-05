<?php

declare(strict_types=1);

namespace App\Domain\Common;

interface DomainEventDispatcherInterface
{
    public function dispatch(DomainEvent $event): void;
}
