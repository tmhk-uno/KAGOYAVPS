<?php

namespace Plugin\colorselect\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;
 
/**
 * @Eccube\EntityExtension("Eccube\Entity\Product")
 */
trait ProductTrait {






    /**
     * @ORM\Column(name="colorimg1", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー画像1",
     *  }
     * )
     */

    private $colorimg1;
 
    public function getColorimg1() {
        return $this->colorimg1;
    }
 
    public function setColorimg1($colorimg1) {
        $this->colorimg1 = $colorimg1;
 
        return $this;
    }



    /**
     * @ORM\Column(name="colortext1", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー名1"
     *  }
     * )
     */

    private $colortext1;

    public function getColortext1() {
        return $this->colortext1;
    }

    public function setColortext1($colortext1) {
        $this->colortext1 = $colortext1;

        return $this;
    }




    /**
     * @ORM\Column(name="colorimg2", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー画像2",
     *  }
     * )
     */

    private $colorimg2;

    public function getColorimg2() {
        return $this->colorimg2;
    }

    public function setColorimg2($colorimg2) {
        $this->colorimg2 = $colorimg2;

        return $this;
    }



    /**
     * @ORM\Column(name="colortext2", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー名2"
     *  }
     * )
     */

    private $colortext2;

    public function getColortext2() {
        return $this->colortext2;
    }

    public function setColortext2($colortext2) {
        $this->colortext2 = $colortext2;

        return $this;
    }


    /**
     * @ORM\Column(name="colorimg3", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー画像3",
     *  }
     * )
     */

    private $colorimg3;

    public function getColorimg3() {
        return $this->colorimg3;
    }

    public function setColorimg3($colorimg3) {
        $this->colorimg3 = $colorimg3;

        return $this;
    }



    /**
     * @ORM\Column(name="colortext3", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー名3"
     *  }
     * )
     */

    private $colortext3;

    public function getColortext3() {
        return $this->colortext3;
    }

    public function setColortext3($colortext3) {
        $this->colortext3 = $colortext3;
        return $this;
    }






    /**
     * @ORM\Column(name="colorimg4", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー画像4",
     *  }
     * )
     */

    private $colorimg4;

    public function getColorimg4() {
        return $this->colorimg4;
    }

    public function setColorimg4($colorimg4) {
        $this->colorimg4 = $colorimg4;

        return $this;
    }



    /**
     * @ORM\Column(name="colortext4", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー名4"
     *  }
     * )
     */

    private $colortext4;

    public function getColortext4() {
        return $this->colortext4;
    }

    public function setColortext4($colortext4) {
        $this->colortext4 = $colortext4;
        return $this;
    }






    /**
     * @ORM\Column(name="colorimg5", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー画像5",
     *  }
     * )
     */

    private $colorimg5;

    public function getColorimg5() {
        return $this->colorimg5;
    }

    public function setColorimg5($colorimg5) {
        $this->colorimg5 = $colorimg5;

        return $this;
    }



    /**
     * @ORM\Column(name="colortext5", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー名5"
     *  }
     * )
     */

    private $colortext5;

    public function getColortext5() {
        return $this->colortext5;
    }

    public function setColortext5($colortext5) {
        $this->colortext5 = $colortext5;
        return $this;
    }



    /**
     * @ORM\Column(name="colorimg6", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー画像6",
     *  }
     * )
     */

    private $colorimg6;

    public function getColorimg6() {
        return $this->colorimg6;
    }

    public function setColorimg6($colorimg6) {
        $this->colorimg6 = $colorimg6;

        return $this;
    }



    /**
     * @ORM\Column(name="colortext6", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー名6"
     *  }
     * )
     */

    private $colortext6;

    public function getColortext6() {
        return $this->colortext6;
    }

    public function setColortext6($colortext6) {
        $this->colortext6 = $colortext6;
        return $this;
    }


    /**
     * @ORM\Column(name="colorimg7", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー画像7",
     *  }
     * )
     */

    private $colorimg7;

    public function getColorimg7() {
        return $this->colorimg7;
    }

    public function setColorimg7($colorimg7) {
        $this->colorimg7 = $colorimg7;

        return $this;
    }



    /**
     * @ORM\Column(name="colortext7", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー名7"
     *  }
     * )
     */

    private $colortext7;

    public function getColortext7() {
        return $this->colortext7;
    }

    public function setColortext7($colortext7) {
        $this->colortext7 = $colortext7;
        return $this;
    }



    /**
     * @ORM\Column(name="colorimg8", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー画像8",
     *  }
     * )
     */

    private $colorimg8;

    public function getColorimg8() {
        return $this->colorimg8;
    }

    public function setColorimg8($colorimg8) {
        $this->colorimg8 = $colorimg8;

        return $this;
    }



    /**
     * @ORM\Column(name="colortext8", type="text", nullable=true)
     * @Eccube\FormAppend(
     *  auto_render=true,
     *  type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *  options={
     *    "required": false,
     *    "label": "カラー名8"
     *  }
     * )
     */

    private $colortext8;

    public function getColortext8() {
        return $this->colortext8;
    }

    public function setColortext8($colortext8) {
        $this->colortext8 = $colortext8;
        return $this;
    }


}

