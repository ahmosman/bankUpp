<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/user/crud')]
class UserCrudController extends AbstractController
{

    private string $managementRoute;

    public function __construct(Security $security)
    {
        if($security->isGranted('ROLE_BANKER'))
        {
            $this->managementRoute = 'app_banker_management';
        }else{
            $this->managementRoute = 'app_main';
        }

    }

    #[Route('/', name: 'app_user_crud_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {

        return $this->render('user_crud/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userRepository->add($user);

            $this->addFlash('success','Pomyślnie dodano użytkownika');

            return $this->redirectToRoute($this->managementRoute, [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_crud/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'managementRoute' => $this->managementRoute
        ]);
    }

    #[Route('/{id}', name: 'app_user_crud_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user_crud/show.html.twig', [
            'user' => $user,
            'managementRoute' => $this->managementRoute
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        dump($this->getUser());
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user);
            $this->addFlash('success','Pomyślnie zaktualizowano użytkownika');
            return $this->redirectToRoute($this->managementRoute, [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_crud/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'managementRoute' => $this->managementRoute
        ]);
    }

    #[Route('/{id}', name: 'app_user_crud_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
            $this->addFlash('success','Pomyślnie usunięto użytkownika');
        }

        return $this->redirectToRoute($this->managementRoute, [], Response::HTTP_SEE_OTHER);
    }
}
