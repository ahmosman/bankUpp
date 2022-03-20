<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\TransferHistory;
use App\Entity\User;
use App\Repository\AccountRepository;
use App\Repository\AccountTypeRepository;
use App\Repository\TransferHistoryRepository;
use App\Repository\UserRepository;

class AccountService
{
    private AccountRepository $accountRepository;
    private AccountTypeRepository $accountTypeRepository;
    private TransferHistoryRepository $historyRepository;
    private UserRepository $userRepository;

    public function __construct(AccountRepository $accountRepository,
                                UserRepository $userRepository,
                                AccountTypeRepository $accountTypeRepository,
                                TransferHistoryRepository $historyRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->accountTypeRepository = $accountTypeRepository;
        $this->historyRepository = $historyRepository;
        $this->userRepository = $userRepository;
    }

    public function createAccount(User $user, int $accountTypeId)
    {
        $accountType = $this->accountTypeRepository->find($accountTypeId);
        if(!$accountType)
            return;
        $account = new Account();
        $account->setUser($user);
        $account->setBalance($accountType->getStartBonus());
        $account->setAccountType($accountType);
        //generate random account code
        $accountCode = "";
        for ($i = 0; $i < 26; $i++){
            $accountCode .= mt_rand(0,9);
        }
        while ($this->accountRepository->findOneBy(['accountCode' => $accountCode])) {
            $accountCode = "";
            for ($i = 0; $i < 26; $i++){
                $accountCode .= mt_rand(0,9);
            }
        }
        $account->setAccountCode($accountCode);
        $this->accountRepository->add($account);
        return $account;
    }

    public function makeTransfer(Account $fromAccount, string $toAccountNumber, int $amount, bool $isPhone)
    {
        /** @var Account $toAccount */
        if($isPhone)
            $toAccount = $this->userRepository->findOneBy(['phoneNumber'=>$toAccountNumber])->getPhoneAccount();
        else
            $toAccount = $this->accountRepository->findOneBy(['accountCode'=>$toAccountNumber]);

        if(!$toAccount or $toAccount === $fromAccount)
            return false;
        $toAccount->setBalance($toAccount->getBalance()+$amount);
        $fromAccount->setBalance($fromAccount->getBalance()-$amount);
        $history = new TransferHistory();
        $history->setAmount($amount);
        $history->setFromAccount($fromAccount);
        $history->setToAccount($toAccount);
        $this->historyRepository->add($history);

        return true;
    }
}