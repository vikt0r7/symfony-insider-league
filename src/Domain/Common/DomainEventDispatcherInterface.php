<?php

namespace App\Domain\Common;

interface DomainEventDispatcherInterface
{
    public function dispatch(DomainEvent $event): void;
}
