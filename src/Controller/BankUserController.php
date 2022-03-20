<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use App\Repository\AccountRepository;
use App\Repository\TransferHistoryRepository;
use App\Repository\UserRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankUserController extends AbstractController
{
    private TransferHistoryRepository $transferRepository;

    public function __construct(TransferHistoryRepository $transferRepository)
    {
        $this->transferRepository = $transferRepository;
    }

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

    #[Route('/history/{page<\d+>}', name: 'app_account_history')]
    public function history(int $page = 1): Response
    {
        $queryBuilder = $this->transferRepository->createTransferHistoryQueryBuilder($this->getUser());
        $pager = new Pagerfanta(new QueryAdapter($queryBuilder));
        $pager->setMaxPerPage(5);
        $pager->setCurrentPage($page);
//        dump($pager);die();
        return $this->render('account/history.html.twig', [
            'pager' => $pager,
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
