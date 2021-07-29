<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use App\Repository\TransferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 */
class Account
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Iban(
     *     message="This is not a valid International Bank Account Number (IBAN)."
     * )
     */
    private $accountNumber;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="accounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Transfer::class, mappedBy="sender")
     */
    private $sentTransfers;

    /**
     * @ORM\OneToMany(targetEntity=Transfer::class, mappedBy="receiver")
     */
    private $receivedTransfers;

    /**
     * @ORM\Column(type="float")
     */
    private $balance;

    public function __construct()
    {
        $this->sentTransfers = new ArrayCollection();
        $this->receivedTransfers = new ArrayCollection();
        $this->balance = 500;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Transfer[]
     */
    public function getSentTransfers(): Collection
    {
        return $this->sentTransfers;
    }

    public function addSentTransfer(Transfer $sentTransfer): self
    {
        if (!$this->sentTransfers->contains($sentTransfer)) {
            $this->sentTransfers[] = $sentTransfer;
            $sentTransfer->setAccountFrom($this);
        }

        return $this;
    }

    public function removeSentTransfer(Transfer $sentTransfer): self
    {
        if ($this->sentTransfers->removeElement($sentTransfer)) {
            // set the owning side to null (unless already changed)
            if ($sentTransfer->getAccountFrom() === $this) {
                $sentTransfer->setAccountFrom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transfer[]
     */
    public function getReceivedTransfers(): Collection
    {
        return $this->receivedTransfers;
    }

    public function addReceivedTransfer(Transfer $receivedTransfer): self
    {
        if (!$this->receivedTransfers->contains($receivedTransfer)) {
            $this->receivedTransfers[] = $receivedTransfer;
            $receivedTransfer->setAccountTo($this);
        }

        return $this;
    }

    public function removeReceivedTransfer(Transfer $receivedTransfer): self
    {
        if ($this->receivedTransfers->removeElement($receivedTransfer)) {
            // set the owning side to null (unless already changed)
            if ($receivedTransfer->getAccountTo() === $this) {
                $receivedTransfer->setAccountTo(null);
            }
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @param int $balance
     */
    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }

    public function __toString() {
        return $this->getAccountNumber() . ": " . $this->getUser()->getFullName() . ", " . $this->getType() . " Account";
    }

    /**
     * @return mixed
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @param mixed $accountNumber
     */
    public function setAccountNumber($accountNumber): void
    {
        $this->accountNumber = $accountNumber;
    }
}
