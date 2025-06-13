<?php

declare(strict_types=1);

namespace App\Application\Service;

use Psr\Log\LoggerInterface;

class NotificationSender
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function send(int $userId, string $message): void
    {
        $this->logger->info("Sending notification to {$userId}: {$message}");

        echo "Notification sent #{$userId}: {$message}".PHP_EOL;
    }
}
