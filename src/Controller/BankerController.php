<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/banker')]
class BankerController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/', name: 'app_banker')]
    public function index(): Response
    {
        return $this->render('banker/index.html.twig', [
            'controller_name' => 'BankerController',
        ]);
    }

    #[Route('/management', name: 'app_banker_management')]
    public function userManagement(): Response
    {
        $bankUsers = $this->userRepository->findByRole('BANK_USER');
        return $this->render('banker/management.html.twig', [
            'bankUsers' => $bankUsers,
        ]);
    }

    #[Route('/new', name: 'app_banker_add_user', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository,  UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_BANK_USER']);
            //            TODO: Tymczasowe hasło
            $hashedPassword = $passwordHasher->hashPassword($user,'qwerty');
            $user->setPassword($hashedPassword);
            $user->setIsVerified(true);            $userRepository->add($user);
            $this->addFlash('success','Pomyślnie dodano użytkownika');
            return $this->redirectToRoute('app_banker_management', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_crud/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'managementRoute' => 'app_banker_management'
        ]);
    }

}
