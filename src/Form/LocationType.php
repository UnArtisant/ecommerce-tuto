<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', TextType::class, [
                "attr" => [
                    "label" => false,
                    "placeholder" => "First name*"
                ],

            ])
            ->add('city', TextType::class, [
                "attr" => [
                    "label" => false,
                    "placeholder" => "First name*"
                ]
            ])
            ->add('state', TextType::class, [
                "attr" => [
                    "label" => false,
                    "placeholder" => "First name*"
                ]
            ])
            ->add('country', TextType::class, [
                "attr" => [
                    "label" => false,
                    "placeholder" => "First name*"
                ]
            ])
            ->add('zipcode', TextType::class, [
                "attr" => [
                    "label" => false,
                    "placeholder" => "First name*"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'inherit_data' => true,
        ]);
    }
}
