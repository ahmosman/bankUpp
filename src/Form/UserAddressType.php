<?php

namespace App\Form;

use App\Entity\UserAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class, [
                'label' => 'Adres zamieszkania',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać adres zamieszkania.',
                    ])
                ]
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Kod pocztowy ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać kod pocztowy.',
                    ]),
                    new Regex([
                        'pattern' => '/^\d{2}[-]{1}\d{3}$/',
                        'message' => 'Niepoprawny format kodu pocztowego.'
                    ]),

                ]])
            ->add('city', TextType::class, [
                'label' => 'Miasto',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać miasto.',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserAddress::class,
        ]);
    }
}
