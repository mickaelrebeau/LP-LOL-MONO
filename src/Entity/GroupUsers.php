<?php

namespace App\Entity;

use App\Repository\GroupUsersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupUsersRepository::class)]
class GroupUsers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $data_sharing_additional = null;

    #[ORM\ManyToOne(inversedBy: 'groupUsers', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'groupUsers', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $group_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataSharingAdditional(): ?string
    {
        return $this->data_sharing_additional;
    }

    public function setDataSharingAdditional(?string $data_sharing_additional): self
    {
        $this->data_sharing_additional = $data_sharing_additional;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getGroupId(): ?Group
    {
        return $this->group_id;
    }

    public function setGroupId(?Group $group_id): self
    {
        $this->group_id = $group_id;

        return $this;
    }
}
