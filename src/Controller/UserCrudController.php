<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/users')]
class UserCrudController extends AbstractController
{

    #[Route('/{typeInfo}/{id}', name: 'app_user_crud_show', methods: ['GET'])]
    public function show(User $user, string $typeInfo): Response
    {
        $accounts = $user->getAccounts();
        $totalBalance = floatval(0);
        /** @var Account $account */
        foreach ($accounts as $account)
            $totalBalance += $account->getBalance();
        $managementRoute =
            ($typeInfo == 'użytkownik') ? 'app_banker_management' :
                (($typeInfo == 'bankier') ? 'app_admin_management': 'app_main');

        return $this->render('user_crud/display_user.html.twig', [
            'user' => $user,
            'managementRoute' => $managementRoute,
            'typeInfo' => $typeInfo,
            'totalBalance' => $totalBalance
        ]);
    }

    #[Route('/{typeInfo}/{id}/edit/', name: 'app_user_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, string $typeInfo): Response
    {
        $managementRoute =
                ($typeInfo == 'użytkownik') ? 'app_banker_management' :
                    (($typeInfo == 'bankier') ? 'app_admin_management': 'app_main');

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user);
            $this->addFlash('success','Pomyślnie zaktualizowano '.$typeInfo.'a');
            return $this->redirectToRoute($managementRoute, [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_crud/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'managementRoute' => $managementRoute,
            'typeInfo' => $typeInfo
        ]);
    }

    #[Route('/{typeInfo}/{id}/delete', name: 'app_user_crud_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository, string $typeInfo): Response
    {
        $managementRoute =
            ($typeInfo == 'użytkownik') ? 'app_banker_management' :
                (($typeInfo == 'bankier') ? 'app_admin_management': 'app_main');

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
            $this->addFlash('success','Pomyślnie usunięto '.$typeInfo.'a');
        }

        return $this->redirectToRoute($managementRoute, [], Response::HTTP_SEE_OTHER);
    }
}
