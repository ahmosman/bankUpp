<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 26)]
    private $accountCode;

    #[ORM\Column(type: 'float')]
    private $balance;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private $user;

    #[ORM\ManyToOne(targetEntity: AccountType::class, inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private $accountType;

    #[ORM\OneToMany(mappedBy: 'fromAccount', targetEntity: TransferHistory::class, orphanRemoval: true)]
    private $transferHistoriesFrom;

    #[ORM\OneToMany(mappedBy: 'toAccount', targetEntity: TransferHistory::class, orphanRemoval: true)]
    private $transferHistoriesTo;

    public function __construct()
    {
        $this->transferHistoriesFrom = new ArrayCollection();
        $this->transferHistoriesTo = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccountCode(): ?string
    {
        return $this->accountCode;
    }

    public function setAccountCode(string $accountCode): self
    {
        $this->accountCode = $accountCode;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAccountType(): ?AccountType
    {
        return $this->accountType;
    }

    public function setAccountType(?AccountType $accountType): self
    {
        $this->accountType = $accountType;

        return $this;
    }

    /**
     * @return Collection<int, TransferHistory>
     */
    public function getTransferHistoriesFrom(): Collection
    {
        return $this->transferHistoriesFrom;
    }

    public function addTransferHistoriesFrom(TransferHistory $transferHistoriesFrom): self
    {
        if (!$this->transferHistoriesFrom->contains($transferHistoriesFrom)) {
            $this->transferHistoriesFrom[] = $transferHistoriesFrom;
            $transferHistoriesFrom->setFromAccount($this);
        }

        return $this;
    }

    public function removeTransferHistoriesFrom(TransferHistory $transferHistoriesFrom): self
    {
        if ($this->transferHistoriesFrom->removeElement($transferHistoriesFrom)) {
            // set the owning side to null (unless already changed)
            if ($transferHistoriesFrom->getFromAccount() === $this) {
                $transferHistoriesFrom->setFromAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TransferHistory>
     */
    public function getTransferHistoriesTo(): Collection
    {
        return $this->transferHistoriesTo;
    }

    public function addTransferHistoriesTo(TransferHistory $transferHistoriesTo): self
    {
        if (!$this->transferHistoriesTo->contains($transferHistoriesTo)) {
            $this->transferHistoriesTo[] = $transferHistoriesTo;
            $transferHistoriesTo->setToAccount($this);
        }

        return $this;
    }

    public function removeTransferHistoriesTo(TransferHistory $transferHistoriesTo): self
    {
        if ($this->transferHistoriesTo->removeElement($transferHistoriesTo)) {
            // set the owning side to null (unless already changed)
            if ($transferHistoriesTo->getToAccount() === $this) {
                $transferHistoriesTo->setToAccount(null);
            }
        }

        return $this;
    }

}
