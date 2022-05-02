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

namespace Plugin\RestockMail\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Master\AbstractMasterEntity;

/**
 * RestockMailStatus
 *
 * @ORM\Table(name="plg_restock_mail_status")
 * @ORM\Entity(repositoryClass="Plugin\RestockMail\Repository\RestockMailStatusRepository")
 */
class RestockMailStatus extends AbstractMasterEntity
{
    /**
     * 表示
     */
    const send = 1;

    /**
     * 非表示
     */
    const nosend = 2;
}
