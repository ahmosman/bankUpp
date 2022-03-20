<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\AccountService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
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
    private AccountService $accountService;

    public function __construct(UserRepository $userRepository, AccountService $accountService)
    {
        $this->userRepository = $userRepository;
        $this->accountService = $accountService;
    }

    #[Route('/', name: 'app_banker')]
    public function index(): Response
    {
        return $this->render('management/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/management/{page<\d+>}', name: 'app_banker_management')]
    public function userManagement(int $page = 1): Response
    {
        $queryBuilder = $this->userRepository->createFindUsersByRoleQueryBuilder('BANK_USER');
        $pager = new Pagerfanta(new QueryAdapter($queryBuilder));
        $pager->setMaxPerPage(5);
        $pager->setCurrentPage($page);
        return $this->render('user_crud/show.html.twig', [
            'pager' => $pager,
            'typeInfo' => 'użytkownik'
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
            $user->setIsVerified(true);
            $account = $this->accountService->createAccount($user,1);
            $user->setPhoneAccount($account);
            $userRepository->add($user);
            $this->addFlash('success','Pomyślnie dodano użytkownika');
            return $this->redirectToRoute('app_banker_management', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_crud/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'typeInfo' => 'użytkownik',
            'managementRoute' => 'app_banker_management'
        ]);
    }

}
