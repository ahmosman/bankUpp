<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
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
        $user = $this->getUser();
        $accounts = $user->getAccounts();
        $postalCode = $user->getAddress()->getPostalCode();
        $postalCode = substr_replace($postalCode, '-', 2, 0);
        $user->getAddress()->setPostalCode($postalCode);
        $totalBalance = floatval(0);
        /** @var Account $account */
        foreach ($accounts as $account)
            $totalBalance += $account->getBalance();

        return $this->render('bank_user/index.html.twig', [
            'user' => $user,
            'totalBalance' => $totalBalance
        ]);
    }

    #[Route('/history', name: 'app_account_history')]
    public function history(Request $request)
    {
        $accounts = $this->getUser()->getAccounts();
        $histories = [];
        /** @var Account $account */
        foreach ($accounts as $account) {
            foreach ($account->getTransferHistoriesFrom() as $fromHistory)
                $histories[] = $fromHistory;
            foreach ($account->getTransferHistoriesTo() as $toHistory)
                $histories[] = $toHistory;
        }
        dump($histories);
        return $this->render('account/history.html.twig', [
            'account' => $account,
            'histories' => $histories,
            'userEmail' => $this->getUser()->getUserIdentifier()
        ]);
    }
}
