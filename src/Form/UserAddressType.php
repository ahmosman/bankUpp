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
            ->add('postalCode', NumberType::class, [
                'label' => 'Kod pocztowy ',
                'attr' => [
                    'minLength' => 5,
                    'maxLength' => 5,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać kod pocztowy.',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Kod pocztowy powinien składać się z {{ limit }} cyfr.',
//                    'max' => 5,
//                    'maxMessage' => 'Kod pocztowy powinien składać się z {{ limit }} cyfr.'
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
