<?php

namespace App\Twig;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class GlobalVariablesSubscriber implements EventSubscriberInterface
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        // Obtenez la requête depuis le contrôleur
        $request = $event->getRequest();

        // Récupérez l'ID de la catégorie à partir de la requête
        $categoryId = $request->get('id');

        // Ajoutez la variable à Twig
        $this->twig->addGlobal('id', $categoryId);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
