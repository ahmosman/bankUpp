<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PhoneTransferFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('toAccount', TextType::class, [

                'label' => 'Numer telefonu odbiorcy',
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
            ->add('amount',NumberType::class,[
                'label' => 'Kwota'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
