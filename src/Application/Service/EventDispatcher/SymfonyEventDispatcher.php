<?php

declare(strict_types=1);

namespace App\Application\Service\EventDispatcher;

use App\Domain\Common\DomainEvent;
use App\Domain\Common\DomainEventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyEventDispatcher implements DomainEventDispatcherInterface
{
    public function __construct(
        private readonly MessageBusInterface $bus,
    ) {
    }

    public function dispatch(DomainEvent $event): void
    {
        $this->bus->dispatch($event);
    }
}
