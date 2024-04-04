<?php

namespace FHPlatform\Bundle\EventManagerBundle\EventListener;

use FHPlatform\Component\EventManager\Manager\EventManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class FrameworkEventListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly EventManager $eventManager
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        dump(2222);
        return [
            KernelEvents::FINISH_REQUEST => 'onKernelFinishRequest',
        ];
    }

    public function onKernelFinishRequest(FinishRequestEvent $event): void
    {

        dd(3333);
        $this->eventManager->requestFinishedEvent();
    }
}
