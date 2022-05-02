<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\RestockMail;

use Eccube\Entity\Product;
use Eccube\Event\TemplateEvent;
use Eccube\Repository\Master\ProductStatusRepository;
use Plugin\RestockMail\Entity\RestockMailStatus;
use Plugin\RestockMail\Repository\RestockMailConfigRepository;
use Plugin\RestockMail\Repository\RestockMailRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RestockMailEvent implements EventSubscriberInterface
{
    /**
     * @var RestockMailConfigRepository
     */
    protected $productReviewConfigRepository;

    /**
     * @var RestockMailRepository
     */
    protected $productReviewRepository;

    /**
     * RestockMail constructor.
     *
     * @param RestockMailConfigRepository $productReviewConfigRepository
     * @param ProductStatusRepository $productStatusRepository
     * @param RestockMailRepository $productReviewRepository
     */
    public function __construct(
        RestockMailConfigRepository $productReviewConfigRepository,
        ProductStatusRepository $productStatusRepository,
        RestockMailRepository $productReviewRepository
    ) {
        $this->productReviewConfigRepository = $productReviewConfigRepository;
        $this->productStatusRepository = $productStatusRepository;
        $this->productReviewRepository = $productReviewRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Product/detail.twig' => 'detail',
        ];
    }

    /**
     * @param TemplateEvent $event
     */
    public function detail(TemplateEvent $event)
    {
        $event->addSnippet('@RestockMail/default/index.twig');
        $parameters = $event->getParameters();
        $event->setParameters($parameters);
    }
}
