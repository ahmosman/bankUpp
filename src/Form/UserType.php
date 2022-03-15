<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Imię',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać imię.',
                    ])
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nazwisko',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać nazwisko.',
                    ])
                ]
            ])
            ->add('pesel', NumberType::class, [

                'label' => 'Pesel',
                'attr' => [
                    'minLength' => 11,
                    'maxLength' => 11
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać numer PESEL.',
                    ]),
                    new Length([
                        'min' => 11,
                        'minMessage' => 'Pesel powinien składać się z {{ limit }} cyfr.',
                        'max' => 11,
                        'maxMessage' => 'Pesel powinien składać się z {{ limit }} cyfr.'
                    ]),
                ],
            ])
            ->add('phoneNumber', NumberType::class, [
                'label' => 'Numer telefonu',
                'attr' => [
                    'minLength' => 9,
                    'maxLength' => 9
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać numer PESEL.',
                    ]),
                    new Length([
                        'min' => 9,
                        'minMessage' => 'Numer telefonu powinien składać się z {{ limit }} cyfr.',
                        'max' => 9,
                        'maxMessage' => 'Numer telefonu powinien składać się z {{ limit }} cyfr.'
                    ]),
                ],
            ])
            ->add('address', UserAddressType::class, [
                'label' => 'Adres:']
            )

            ->add('email', TextType::class, [
                'label' => 'Adres e-mail',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać adres e-mail.',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
