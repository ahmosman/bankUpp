<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TransferFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('toAccountNumber', TextType::class, [

                'label' => 'Numer konta docelowego',
//                'attr' => [
//                    'minLength' => 26,
//                    'maxLength' => 26
//                ],
//                'constraints' => [
//                    new NotBlank([
//                        'message' => 'Proszę podać numer konta docelowego.',
//                    ]),
//                    new Length([
//                        'min' => 26,
//                        'minMessage' => 'Numer konta docelowego składać się z {{ limit }} cyfr.',
//                        'max' => 26,
//                        'maxMessage' => 'Numer konta docelowego składać składać się z {{ limit }} cyfr.'
//                    ]),
//                ],
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
