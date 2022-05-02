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

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Layout;
use Eccube\Entity\Page;
use Eccube\Entity\PageLayout;
use Eccube\Plugin\AbstractPluginManager;
use Eccube\Repository\PageRepository;
use Plugin\RestockMail\Entity\RestockMailConfig;
use Plugin\RestockMail\Entity\RestockMailStatus;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PluginManager extends AbstractPluginManager
{


    public function enable(array $meta, ContainerInterface $container)
    {
        $em = $container->get('doctrine.orm.entity_manager');

        // プラグイン設定を追加
        $Config = $this->createConfig($em);

        // お知らせメールステータス(未送信・送信済み)を追加
        $this->createStatus($em);



    }

    public function uninstall(array $meta, ContainerInterface $container)
    {



    }

    protected function createConfig(EntityManagerInterface $em)
    {
        $Config = $em->find(RestockMailConfig::class, 1);
        if ($Config) {
            return $Config;
        }
        $Config = new RestockMailConfig();

        $Config->setSubject("再入荷お知らせメール");
        $Config->setCode("1234");
        $em->persist($Config);
        $em->flush($Config);

        return $Config;
    }

    protected function createStatus(EntityManagerInterface $em)
    {
        $Status = $em->find(RestockMailStatus::class, 1);
        if ($Status) {
            return;
        }

        $Status = new RestockMailStatus();
        $Status->setId(1);
        $Status->setName('送信済み');
        $Status->setSortNo(1);

        $em->persist($Status);
        $em->flush($Status);

        $Status = new RestockMailStatus();
        $Status->setId(2);
        $Status->setName('未送信');
        $Status->setSortNo(2);

        $em->persist($Status);
        $em->flush($Status);
    }





}
