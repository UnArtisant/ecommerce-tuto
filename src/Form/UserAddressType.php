<?php

namespace App\Form;

use App\Entity\UserAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("location", LocationType::class, [
                'data_class' => UserAddress::class
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
