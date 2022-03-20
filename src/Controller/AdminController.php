<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/admin')]
class AdminController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('management/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/management/{page<\d+>}', name: 'app_admin_management')]
    public function adminBankersManagement(int $page = 1): Response
    {
        $queryBuilder = $this->userRepository->createFindUsersByRoleQueryBuilder('BANKER');
        $pager = new Pagerfanta(new QueryAdapter($queryBuilder));
        $pager->setMaxPerPage(5);
        $pager->setCurrentPage($page);
        return $this->render('user_crud/show.html.twig', [
            'pager' => $pager,
            'typeInfo' => 'bankier'
        ]);
    }


    #[Route('/new', name: 'app_admin_add_banker', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository,  UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_BANKER']);
            //            TODO: Tymczasowe hasło
            $hashedPassword = $passwordHasher->hashPassword($user,'qwerty');
            $user->setPassword($hashedPassword);
            $user->setIsVerified(true);
            $userRepository->add($user);
            $this->addFlash('success','Pomyślnie dodano bankiera');
            return $this->redirectToRoute('app_admin_management', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_crud/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'typeInfo' => 'bankier',
            'managementRoute' => 'app_admin_management'
        ]);
    }
}
