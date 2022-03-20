<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use App\Entity\UserAddress;
use App\Form\RegistrationFormType;
use App\Repository\UserAddressRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\UserAuthenticator;
use App\Service\AccountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private AccountService $accountService;

    public function __construct(EmailVerifier $emailVerifier, AccountService $accountService)
    {
        $this->emailVerifier = $emailVerifier;
        $this->accountService = $accountService;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserAddressRepository $addressRepository, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $confirmMessage = false;

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_BANK_USER']);
            $addressArr = [
                'address' => $form->get('address')->getData(),
                'postalCode' => $form->get('postalCode')->getData(),
                'city' => $form->get('city')->getData()
            ];
//            dump($addressArr);
            $existingAddress = $addressRepository->findExistingAddress($addressArr);

            if(!$existingAddress)
            {
                $newAddress = new UserAddress();
                $newAddress->setAddress($addressArr['address']);
                $newAddress->setPostalCode($addressArr['postalCode']);
                $newAddress->setCity($addressArr['city']);
                $entityManager->persist($newAddress);
                $user->setAddress($newAddress);
            }else{
                $user->setAddress($existingAddress);
            }
            //dodanie nowego konta do użytkownika
            $account = $this->accountService->createAccount($user, 1);
            // przypisanie konta jako cel przelewów na telefon
            $user->setPhoneAccount($account);

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('ahmee126@gmail.com', 'BankUpp'))
                    ->to($user->getEmail())
                    ->subject('Potwierdź rejestrację w naszym banku')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            $confirmMessage = true;
            // do anything else you need here, like send an email

//            return $userAuthenticator->authenticateUser(
//                $user,
//                $authenticator,
//                $request
//            );
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'confirmMessage' => $confirmMessage
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_login');
        }

        $this->addFlash('success', 'Pomyślnie zarejestrowano się w serwisie BankUpp. Możesz się zalogować.');

        return $this->redirectToRoute('app_login');
    }
}
