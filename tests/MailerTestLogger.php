<?php

namespace App\Tests;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mailer\Event\MessageEvents;

class MailerTestLogger implements EventSubscriberInterface
{
    private array $events = [];

    public static function getSubscribedEvents(): array
    {
        return [
            MessageEvents::class => 'onMessageSend',
        ];
    }

    public function onMessageSend(MessageEvent $event)
    {
        $this->events[] = $event;
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
