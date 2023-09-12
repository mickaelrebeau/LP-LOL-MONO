<?php

namespace App\Entity;

use App\Repository\RequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
class RequestAccess
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_requester = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_target = null;

    #[ORM\OneToMany(mappedBy: 'request_id', targetEntity: RequestLog::class, orphanRemoval: true)]
    private Collection $requestLogs;

    public function __construct()
    {
        $this->requestLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUserRequester(): ?User
    {
        return $this->user_requester;
    }

    public function setUserRequester(?User $user_requester): self
    {
        $this->user_requester = $user_requester;

        return $this;
    }

    public function getUserTarget(): ?User
    {
        return $this->user_target;
    }

    public function setUserTarget(?User $user_target): self
    {
        $this->user_target = $user_target;

        return $this;
    }

    /**
     * @return Collection<int, RequestLog>
     */
    public function getRequestLogs(): Collection
    {
        return $this->requestLogs;
    }

    public function addRequestLog(RequestLog $requestLog): self
    {
        if (!$this->requestLogs->contains($requestLog)) {
            $this->requestLogs->add($requestLog);
            $requestLog->setRequestId($this);
        }

        return $this;
    }

    public function removeRequestLog(RequestLog $requestLog): self
    {
        if ($this->requestLogs->removeElement($requestLog)) {
            // set the owning side to null (unless already changed)
            if ($requestLog->getRequestId() === $this) {
                $requestLog->setRequestId(null);
            }
        }

        return $this;
    }
}
