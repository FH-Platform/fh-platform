<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\EventListener;

use FHPlatform\Component\Persistence\Manager\EventManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;

class KernelEventListener implements EventSubscriberInterface
{
    public function __construct(
        // private readonly EventManager $eventManager,
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
        // TODO
        // $this->eventManager->eventRequestFinished();

        // dump('onKernelFinishRequest');
    }
}
