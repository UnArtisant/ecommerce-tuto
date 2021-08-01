<?php

namespace App\Form;

use App\Entity\UserAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', TextType::class, [
                "attr" => [
                    "label" => false,
                    "placeholder"=>"First name*"
                ],

            ])
            ->add('city', TextType::class, [
                "attr" => [
                    "label" => false,
                    "placeholder"=>"First name*"
                ]
            ])
            ->add('state', TextType::class, [
                "attr" => [
                    "label" => false,
                    "placeholder"=>"First name*"
                ]
            ])
            ->add('country', TextType::class, [
                "attr" => [
                    "label" => false,
                    "placeholder"=>"First name*"
                ]
            ])
            ->add('zipcode', TextType::class, [
                "attr" => [
                    "label" => false,
                    "placeholder"=>"First name*"
                ]
            ])
            ->add('isDefaultAddress', ChoiceType::class, [
                "label" => "Default adress ?",
                "choices" => ["Yes" => true, "No" => false]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserAddress::class,
            'csrf_protection' => false,
        ]);
    }
}
