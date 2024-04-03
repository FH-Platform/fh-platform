<?php

namespace FHPlatform\Bundle\EventManagerBundle\EventListener;

use FHPlatform\Component\EventManager\Manager\EventManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;

class FrameworkEventListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly EventManager $eventManager
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FinishRequestEvent::class => 'onKernelFinishRequest',
        ];
    }

    public function onKernelFinishRequest(FinishRequestEvent $event): void
    {
        $this->eventManager->requestFinishedEvent();
    }
}
