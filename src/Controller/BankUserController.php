<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use App\Repository\AccountRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankUserController extends AbstractController
{
    #[IsGranted("ROLE_BANK_USER")]
    #[Route('/profile', name: 'app_bank_user')]
    public function bankUserDashboard(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $accounts = $user->getAccounts();
        $totalBalance = floatval(0);
        $phoneAccount = $user->getPhoneAccount();
        /** @var Account $account */
        foreach ($accounts as $account)
            $totalBalance += $account->getBalance();

        return $this->render('bank_user/index.html.twig', [
            'user' => $user,
            'totalBalance' => $totalBalance,
            'phoneAccountId' => ($phoneAccount) ? $phoneAccount->getId() : false
        ]);
    }

    #[Route('/history', name: 'app_account_history')]
    public function history()
    {
        $accounts = $this->getUser()->getAccounts();
        $histories = [];
        /** @var Account $account */
        foreach ($accounts as $account) {
            foreach ($account->getTransferHistoriesFrom() as $fromHistory)
                $histories[$fromHistory->getId()] = $fromHistory;
            foreach ($account->getTransferHistoriesTo() as $toHistory)
                $histories[$toHistory->getId()] = $toHistory;
        }
        //usunięcie duplikatów przelewów na własne konta
        usort($histories, function($a, $b)
        {
            return $a->getDate() < $b->getDate();
        });
        return $this->render('account/history.html.twig', [
            'account' => $account,
            'histories' => $histories,
            'userEmail' => $this->getUser()->getUserIdentifier()
        ]);
    }

    #[Route('/phoneAccount/{id}', name: 'app_set_phone_transfer')]
    public function setPhoneAccount(int $id, AccountRepository $accountRepository, UserRepository $userRepository)
    {
        $account = $accountRepository->find($id);
        if(!$account)
            return $this->redirectToRoute('app_bank_user');
        if($account->getUser()->getId() !== $this->getUser()->getId()) {
            return $this->redirectToRoute('app_bank_user');
        }

        $this->getUser()->setPhoneAccount($account);
        $userRepository->add($this->getUser());
        $this->addFlash('success', 'Pomyślnie zmieniono konto przelewów na telefon');
        return $this->redirectToRoute('app_bank_user');

    }
}
