<?php

namespace App\Entity;

use App\Repository\TransferHistoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransferHistoryRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class TransferHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    #[ORM\Column(type: 'float')]
    private $amount;

    #[ORM\Column(type: 'datetime',nullable: true, options: ["default"=>"CURRENT_TIMESTAMP"])]
    private $date;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'transferHistoriesFrom')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private $fromAccount;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'transferHistoriesTo')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private $toAccount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setDate(): self
    {
        $this->date = new \DateTime();

        return $this;
    }

    public function getFromAccount(): ?Account
    {
        return $this->fromAccount;
    }

    public function setFromAccount(?Account $fromAccount): self
    {
        $this->fromAccount = $fromAccount;

        return $this;
    }

    public function getToAccount(): ?Account
    {
        return $this->toAccount;
    }

    public function setToAccount(?Account $toAccount): self
    {
        $this->toAccount = $toAccount;

        return $this;
    }

}
