<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
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
                        'max' => 11,
                        'exactMessage' => 'Pesel powinien składać się z {{ limit }} cyfr.'
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
                        'message' => 'Proszę podać numer telefonu.',
                    ]),
                    new Length([
                        'min' => 9,
                        'max' => 9,
                        'exactMessage' => 'Numer telefonu powinien składać się z {{ limit }} cyfr.'
                    ]),
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adres zamieszkania',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać adres zamieszkania.',
                    ])
                ]
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Kod pocztowy ',
                'mapped' => false,
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
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać miasto.',
                    ])
                ]
            ])
            ->add('email', TextType::class, [
                'label' => 'Adres e-mail',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać adres e-mail.',
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Wyrażam zgodę na przetwarzanie danych osobowych.',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Należy zaakceptować warunki.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'invalid_message' => 'Hasła muszą się zgadzać',
                'first_options' => [
                    'label' => 'Wprowadź hasło',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Proszę podać hasło.',
                        ]),
                        new Regex([
                            'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!]).{8,}$/',
                            'message' => 'Niepoprawny format hasła.'
                        ]),
                    ]
                ],
                'second_options' => [
                    'label' => 'Powtórz hasło',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Proszę powtórzyć hasło.',
                        ])
                    ]
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
