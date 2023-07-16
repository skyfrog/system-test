<?php

namespace App\Validation\Form\Type\Product;

use App\Domain\Product\Request\CalcPriceRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalcPriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product')
            ->add('taxNumber')
            ->add('couponCode')
            ->add('paymentProcessor');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CalcPriceRequest::class,
            'csrf_protection' => false
        ]);
    }
}