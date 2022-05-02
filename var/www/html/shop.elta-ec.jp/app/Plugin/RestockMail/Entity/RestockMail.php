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
use Eccube\Entity\AbstractEntity;
use Eccube\Entity\Customer;
use Eccube\Entity\Master\Sex;
use Eccube\Entity\Product;

/**
 * RestockMail
 *
 * @ORM\Table(name="plg_restock_mail")
 * @ORM\Entity(repositoryClass="Plugin\RestockMail\Repository\RestockMailRepository")
 */
class RestockMail extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $Product;


    /**
     * @var ProductCode
     *
     * @ORM\Column(name="product_code", type="text")
     */
    private $ProductCode;

    /**
     * @var ProductCode
     *
     * @ORM\Column(name="product_class_id", type="text")
     */
    private $ProductClassID;




    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Customer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     * })
     */
    private $Customer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetimetz")
     */
    private $create_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetimetz")
     */
    private $update_date;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="send_date", type="datetimetz",nullable=true)
     */
    private $send_date;


    /**
     * @var \Plugin\RestockMail\Entity\RestockMailStatus
     *
     * @ORM\ManyToOne(targetEntity="Plugin\RestockMail\Entity\RestockMailStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     * })
     */




    private $Status;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Get ProductCode.
     *
     * @return string
     */
    public function getProductCode()
    {
        return $this->ProductCode;
    }

    /**
     * Set ProductCode.
     *
     * @param string $reviewer_url
     *
     * @return RestockMail
     */
    public function setProductCode($ProductCode)
    {
        $this->ProductCode = $ProductCode;

        return $this;
    }


    /**
     * Get ProductCode.
     *
     * @return string
     */
    public function getProductClassID()
    {
        return $this->ProductClassID;
    }

    /**
     * Set ProductCode.
     *
     *
     * @return RestockMail
     */
    public function setProductClassID($ProductClassID)
    {
        $this->ProductClassID = $ProductClassID;

        return $this;
    }




    /**
     * Set Product.
     *
     * @param Product $Product
     *
     * @return $this
     */
    public function setProduct(Product $Product)
    {
        $this->Product = $Product;

        return $this;
    }

    /**
     * Get Product.
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->Product;
    }

    /**
     * Set Customer.
     *
     * @param Customer $Customer
     *
     * @return $this
     */
    public function setCustomer(Customer $Customer)
    {
        $this->Customer = $Customer;

        return $this;
    }

    /**
     * Get Customer.
     *
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->Customer;
    }

    /**
     * @return \Plugin\RestockMail\Entity\RestockMailStatus
     */
    public function getStatus()
    {
        return $this->Status;
    }

    /**
     * @param \Plugin\RestockMail\Entity\RestockMailStatus $status
     */
    public function setStatus(\Plugin\RestockMail\Entity\RestockMailStatus $Status)
    {
        $this->Status = $Status;
    }

    /**
     * Set create_date.
     *
     * @param \DateTime $createDate
     *
     * @return $this
     */
    public function setCreateDate($createDate)
    {
        $this->create_date = $createDate;

        return $this;
    }

    /**
     * Get create_date.
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * Set update_date.
     *
     * @param \DateTime $updateDate
     *
     * @return $this
     */
    public function setUpdateDate($updateDate)
    {
        $this->update_date = $updateDate;

        return $this;
    }

    /**
     * Get update_date.
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }



    /**
     * Set send_date.
     *
     * @param \DateTime $snedDate
     *
     * @return $this
     */
    public function setSendDate($sendDate)
    {
        $this->send_date = $sendDate;

        return $this;
    }

    /**
     * Get send_date.
     *
     * @return \DateTime
     */
    public function getSendDate()
    {
        return $this->send_date;
    }


}
