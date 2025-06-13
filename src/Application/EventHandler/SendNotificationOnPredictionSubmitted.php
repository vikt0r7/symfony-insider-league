<?php

declare(strict_types=1);

namespace App\Application\EventHandler;

use App\Application\Event\PredictionSubmittedEvent;
use App\Application\Service\NotificationSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendNotificationOnPredictionSubmitted implements EventSubscriberInterface
{
    public function __construct(
        private readonly NotificationSender $notificationSender,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PredictionSubmittedEvent::class => 'onPredictionSubmitted',
        ];
    }

    public function onPredictionSubmitted(PredictionSubmittedEvent $event): void
    {
        $this->notificationSender->send(
            $event->getUserId(),
            sprintf('✅ Prediction successfully submitted for match %s!', $event->getMatchId())
        );
    }
}
