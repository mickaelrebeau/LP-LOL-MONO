<?php

namespace App\Entity;

use App\Repository\RequestLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestLogRepository::class)]
class RequestLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\ManyToOne(inversedBy: 'requestLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RequestAccess $request_id = null;

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

    public function getRequestId(): ?RequestAccess
    {
        return $this->request_id;
    }

    public function setRequestId(?RequestAccess $request_id): self
    {
        $this->request_id = $request_id;

        return $this;
    }
}
