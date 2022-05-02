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

namespace Plugin\colorselect;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Layout;
use Eccube\Entity\Page;
use Eccube\Entity\PageLayout;
use Eccube\Plugin\AbstractPluginManager;
use Eccube\Repository\PageRepository;
use Plugin\colorselect\Entity\ColorselectConfig;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PluginManager extends AbstractPluginManager
{


    public function enable(array $meta, ContainerInterface $container)
    {
        $em = $container->get('doctrine.orm.entity_manager');

        // プラグイン設定を追加
        $Config = $this->createConfig($em);

    }

    public function uninstall(array $meta, ContainerInterface $container)
    {



    }

    protected function createConfig(EntityManagerInterface $em)
    {
        $Config = $em->find(ColorselectConfig::class, 1);
        if ($Config) {
            return $Config;
        }
        $Config = new ColorselectConfig();

        $Config->setStockMin(1);
        $em->persist($Config);
        $em->flush($Config);
        return $Config;
    }




}
