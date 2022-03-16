<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use App\Form\TransferFormType;
use App\Repository\AccountRepository;
use App\Service\AccountService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account')]
#[IsGranted('ROLE_BANK_USER')]
class AccountController extends AbstractController
{
    private AccountRepository $accountRepository;
    private AccountService $accountService;

// TODO: ustawienie głównego konta
    public function __construct(AccountRepository $accountRepository, AccountService $accountService)
    {
        $this->accountRepository = $accountRepository;
        $this->accountService = $accountService;
    }

    #[Route('/create/{accountType}', name: 'app_account_create')]
    public function create(int $accountType): Response
    {
        $this->accountService->createAccount($this->getUser(), $accountType);

        $this->addFlash('success', 'Pomyślnie dodano nowe konto');

        return $this->redirectToRoute('app_bank_user');
    }

    #[Route('/transfer/{id}', name: 'app_account_transfer')]
    public function transfer(Request $request, Account $fromAccount)
    {
        if ($fromAccount->getUser()->getId() !== $this->getUser()->getId())
            return $this->redirectToRoute('app_main');
        $form = $this->createForm(TransferFormType::class);
        $form->handleRequest($request);
        dump($form);
        if ($form->isSubmitted() && $form->isValid()) {
            $toAccountNumber = $form->get('toAccountNumber')->getData();
            $amount = $form->get('amount')->getData();

            if($this->accountService->makeTransfer($fromAccount, $toAccountNumber, $amount)) {
                $this->addFlash('success', 'Przelew został wykonany');
                return $this->redirectToRoute('app_bank_user');
            }else{
                $this->addFlash('success', 'Nieprawidłowy numer konta');
            }

        }
        return $this->render('account/transfer.html.twig',[
            'form' => $form->createView(),
            'fromAccount' => $fromAccount
        ]);
    }

}
