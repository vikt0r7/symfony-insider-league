<?php

declare(strict_types=1);

namespace App\Application\EventHandler;

use App\Application\Service\NotificationSender;
use App\Domain\Event\PredictionSubmittedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendNotificationOnPredictionSubmitted implements EventSubscriberInterface
{
    public function __construct(
        private NotificationSender $notificationSender
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
            sprintf('✅ Вы успешно отправили прогноз на матч %s!', $event->getMatchId())
        );
    }
}
