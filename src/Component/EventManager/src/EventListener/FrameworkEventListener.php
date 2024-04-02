<?php

namespace FHPlatform\Component\EventManager\EventListener;

use FHPlatform\Component\Persistence\Event\ChangedEntity;
use FHPlatform\Component\Persistence\Event\ChangedEntityPreDelete;
use FHPlatform\Component\Persistence\Event\Flush;
use FHPlatform\Component\EventManager\Manager\EventManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

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
        $this->eventManager->requestFinished();
    }
}
