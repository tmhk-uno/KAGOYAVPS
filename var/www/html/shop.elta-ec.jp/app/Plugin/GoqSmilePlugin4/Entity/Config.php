<?php

namespace Plugin\GoqSmilePlugin4\Entity;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Plugin\GoqSmilePlugin4\Entity\Config', false)) {
    /**
     * Config
     *
     * @ORM\Table(name="plg_goq_smile_plugin4_config")
     * @ORM\Entity(repositoryClass="Plugin\GoqSmilePlugin4\Repository\ConfigRepository")
     */
    class Config
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
         * @var string
         *
         * @ORM\Column(name="app_id", type="string", length=255)
         */
        private $app_id;

        /**
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set app_id
         *
         * @param string $appId
         * @return Config
         */
        public function setAppId($appId)
        {
            $this->app_id = $appId;

            return $this;
        }

        /**
         * Get app_id
         *
         * @return string
         */
        public function getAppId()
        {
            return $this->app_id;
        }
    }
}
