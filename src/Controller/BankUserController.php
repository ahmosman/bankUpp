<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $postalCode = substr_replace($postalCode, '-',2,0);
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
}
