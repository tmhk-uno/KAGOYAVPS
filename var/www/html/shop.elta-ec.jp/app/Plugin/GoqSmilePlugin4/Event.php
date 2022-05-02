<?php

namespace Plugin\GoqSmilePlugin4;

use Eccube\Event\TemplateEvent;
use Plugin\GoqSmilePlugin4\Repository\ConfigRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Event implements EventSubscriberInterface
{
    /**
     * @var ConfigRepository
     */
    protected $ConfigRepository;

    /**
     * ProductReview constructor.
     *
     * @param ConfigRepository $ConfigRepository
     */
    public function __construct(ConfigRepository $ConfigRepository)
    {
        $this->ConfigRepository = $ConfigRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'default_frame.twig' => ['ConfigTwig'],
        ];
    }

    /**
     * @param TemplateEvent $event
     */
    public function ConfigTwig(TemplateEvent $event)
    {
        $Config = $this->ConfigRepository->get();
        if($Config){
            $parameters = $event->getParameters();
            $parameters['app_id'] = $Config->getAppId();
            $event->setParameters($parameters);
            $twig = '@GoqSmilePlugin4/script/snippet.twig';
            $event->addSnippet($twig);
        }
    }
}

