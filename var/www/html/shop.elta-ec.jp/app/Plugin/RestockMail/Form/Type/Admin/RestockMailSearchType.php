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

namespace Plugin\RestockMail\Form\Type\Admin;

use Eccube\Common\EccubeConfig;
use Eccube\Form\Type\Master\SexType;
use Plugin\RestockMail\Entity\RestockMailStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RestockMailSearchType.
 * [再入荷お知らせメール]-[お知らせメール検索]用Form.
 */
class RestockMailSearchType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * RestockMailSearchType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(EccubeConfig $eccubeConfig)
    {
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * {@inheritdoc}
     * build form method.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $config = $this->eccubeConfig;
        $builder
            ->add('multi', TextType::class, [
                'label' => 'restock_mail.admin.restock_mail.search_multi',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $config['eccube_stext_len']]),
                ],
            ])
            ->add('product_name', TextType::class, [
                'label' => 'restock_mail.admin.restock_mail.search_product_name',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $config['eccube_stext_len']]),
                ],
            ])
            ->add('product_code', TextType::class, [
                'label' => 'restock_mail.admin.restock_mail.search_product_code',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $config['eccube_stext_len']]),
                ],
            ])
            ->add('customer', TextType::class, [
                'label' => '顧客番号',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $config['eccube_stext_len']]),
                ],
            ])
            ->add('sex', SexType::class, [
                'label' => 'restock_mail.admin.restock_mail.search_sex',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('recommend_level', ChoiceType::class, [
                'label' => 'restock_mail.admin.restock_mail.search_recommend_level',
                'choices' => array_flip([
                    '5' => '★★★★★',
                    '4' => '★★★★',
                    '3' => '★★★',
                    '2' => '★★',
                    '1' => '★',
                ]),
                'placeholder' => 'restock_mail.admin.restock_mail.search_recommend_level',
                'expanded' => false,
                'multiple' => false,
                'required' => false,
            ])
            ->add('review_start', DateType::class, [
                'label' => 'restock_mail.admin.restock_mail.search_posted_date_start',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#'.$this->getBlockPrefix().'_review_start',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('review_end', DateType::class, [
                'label' => 'restock_mail.admin.restock_mail.search_posted_date_end',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#'.$this->getBlockPrefix().'_review_end',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('status', EntityType::class, [
                'class' => RestockMailStatus::class,
                'label' => 'restock_mail.admin.restock_mail.search_review_status',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
            ]);
    }
}
