<?php

/*
 * This file is part of BannerManagement4
 *
 * Copyright(c) U-Mebius Inc. All Rights Reserved.
 *
 * https://umebius.com/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\BannerManagement4\Tests\Admin\Web;

use Eccube\Common\Constant;
use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;
use Plugin\BannerManagement4\Entity\Banner;
use Plugin\BannerManagement4\Repository\BannerFieldRepository;
use Plugin\BannerManagement4\Repository\BannerRepository;

/**
 * Class BannerControllerTest.
 */
class BannerControllerTest extends AbstractAdminWebTestCase
{
    /**
     * @var BannerRepository
     */
    private $bannerRepository;

    /**
     * @var BannerFieldRepository
     */
    private $bannerFieldRepository;

    public function setUp()
    {
        parent::setUp();

        if (\version_compare(Constant::VERSION, '4.1-beta', '>=')) {
            $container = self::$container;
        } else {
            $container = $this->container;
        }

        $this->bannerRepository = $container->get(BannerRepository::class);
        $this->bannerFieldRepository = $container->get(BannerFieldRepository::class);
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Test render index.
     */
    public function testIndex()
    {
        $this->client->request('GET', $this->generateUrl('admin_content_banner'));
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testListEmpty()
    {
        $this->deleteAllRows(['plg_banner']);
        $crawler = $this->client->request('GET', $this->generateUrl('admin_content_banner'));
        $this->assertContains('0件が該当しました', $crawler->html());
    }

    public function testListCount()
    {
        $this->deleteAllRows(['plg_banner']);
        for ($i = 0; $i < 3; ++$i) {
            $this->createBanner();
        }

        $crawler = $this->client->request('GET', $this->generateUrl('admin_content_banner'));
        $this->assertContains('3件が該当しました', $crawler->html());
    }

    /**
     * @param int $fieldId
     * @return Banner
     */
    private function createBanner($fieldId = 1)
    {
        $fake = $this->getFaker();

        $qb = $this->entityManager->createQueryBuilder();
        $max = $qb->select('MAX(banner.sort_no)')
            ->from(Banner::class, 'banner')
            ->andWhere('banner.Field = :fieldId')
            ->setParameter('fieldId', $fieldId)
            ->getQuery()
            ->getSingleScalarResult();

        $Banner = new Banner();
        $Banner->setField($this->bannerFieldRepository->find($fieldId));
        $Banner->setFileName($fake->text(10));
        $Banner->setSortNo($max + 1);
        $this->entityManager->persist($Banner);
        $this->entityManager->flush();

        return $Banner;
    }
}
