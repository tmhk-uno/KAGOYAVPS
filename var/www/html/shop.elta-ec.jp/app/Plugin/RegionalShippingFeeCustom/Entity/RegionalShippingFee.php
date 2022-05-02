<?php

namespace Plugin\RegionalShippingFeeCustom\Entity;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Plugin\RegionalShippingFeeCustom\Entity\RegionalShippingFee', false)) {
    /**
     * RegionalShippingFee
     *
     * @ORM\Table(name="plg_regional_shipping_fee_custom_config")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Customize\Repository\RegionalShippingFeeRepository")
     */

    class RegionalShippingFee extends \Eccube\Entity\AbstractEntity
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
         * @var string|null
         *
         * @ORM\Column(name="fee_a", type="decimal", precision=12, scale=0, nullable=true, options={"unsigned":true,"default":0})
         */
        private $fee_a;

        /**
         * @var string|null
         *
         * @ORM\Column(name="fee_b", type="decimal", precision=12, scale=0, nullable=true, options={"unsigned":true,"default":0})
         */
        private $fee_b;

        /**
         * @var string
         *
         * @ORM\Column(name="regional_list", type="text", length=65532, nullable=false, options={"default":0})
         */
        private $regional_list;

        /**
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set fee_a.
         *
         * @param string|null $fee_a
         *
         * @return $this
         */
        public function setFeeA($fee_a)
        {
            $this->fee_a = $fee_a;

            return $this;
        }

        /**
         * Get fee_a.
         *
         * @return string|null
         */
        public function getFeeA()
        {
            return $this->fee_a;
        }

        /**
         * Set fee_b.
         *
         * @param string|null $fee_b
         *
         * @return $this
         */
        public function setFeeB($fee_b)
        {
            $this->fee_b = $fee_b;

            return $this;
        }

        /**
         * Get fee_b.
         *
         * @return string|null
         */
        public function getFeeB()
        {
            return $this->fee_b;
        }


        /**
         * Set regional_list.
         *
         * @return $this
         */
        public function setRegionalList($regional_list)
        {
            $this->regional_list = $regional_list;

            return $this;
        }

        /**
         * Get regional_list.
         *
         * @return string|null
         */
        public function getRegionalList()
        {
            return $this->regional_list;
        }

        public function getPostalCodeLists()
        {
            $postalCodeList = $this->getRegionalList();
            $array = explode(',', $postalCodeList);
            return $array;
        }

    }
}
