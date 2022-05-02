<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\RegionalShippingFeeCustom\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\RegionalShippingFeeCustom\Entity\RegionalShippingFee;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * RegionalShippingFeeRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RegionalShippingFeeRepository extends AbstractRepository
{
    /**
     * RegionalShippingFeeRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RegionalShippingFee::class);
    }

    /**
     * @param int $id
     *
     * @return
     */
    public function get($id = 1)
    {
        $regionalShippingFee = $this->find($id);

        if (null === $regionalShippingFee) {
            throw new \Exception('Regional not found. id = '.$id);
        }

        return $this->find($id);
    }

}