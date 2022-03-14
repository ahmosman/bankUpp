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
            ->add('pesel', IntegerType::class, [

                'label' => 'Pesel',
                'attr' => [
                    'min' => 10000000000,
                    'max' => 99999999999
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
            ->add('address', TextType::class, [
                'label' => 'Adres zamieszkania',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać adres zamieszkania.',
                    ])
                ]
            ])
            ->add('postalCode', NumberType::class, [
                'label' => 'Kod pocztowy ',
                'mapped' => false,
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
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Twoje hasło powinno składać się z przynajmniej {{ limit }} znaków.',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
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
