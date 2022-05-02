<?php

namespace Plugin\RegionalShippingFeeCustom\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Eccube\Form\Type\PriceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegionalShippingFeeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('fee_a', TextType::class, [
            'required' => false,
            'attr' => [
                'required' => 'required',
            ]
        ])
        ->add('fee_b', TextType::class, [
            'required' => false,
            'attr' => [
                'required' => 'required',
            ]
        ])
        ->add('regional_list', TextareaType::class, []);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Plugin\RegionalShippingFeeCustom\Entity\RegionalShippingFee',
        ]);
    }
}

