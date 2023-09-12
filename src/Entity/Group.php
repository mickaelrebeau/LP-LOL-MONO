<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $is_default = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $data_sharing = null;

    #[ORM\ManyToOne(inversedBy: 'groups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $expiration_date = null;

    #[ORM\OneToMany(mappedBy: 'group_id', targetEntity: GroupUsers::class, orphanRemoval: true, cascade: ['persist', 'remove'] )]
    private Collection $groupUsers;

    public function __construct()
    {
        $this->groupUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isIsDefault(): ?bool
    {
        return $this->is_default;
    }

    public function setIsDefault(bool $is_default): self
    {
        $this->is_default = $is_default;

        return $this;
    }

    public function getDataSharing(): ?string
    {
        return $this->data_sharing;
    }

    public function setDataSharing(string $data_sharing): self
    {
        $this->data_sharing = $data_sharing;

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

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expiration_date;
    }

    public function setExpirationDate(\DateTimeInterface $expiration_date): self
    {
        $this->expiration_date = $expiration_date;

        return $this;
    }

    /**
     * @return Collection<int, GroupUsers>
     */
    public function getGroupUsers(): Collection
    {
        return $this->groupUsers;
    }

    public function addGroupUser(GroupUsers $groupUser): self
    {
        if (!$this->groupUsers->contains($groupUser)) {
            $this->groupUsers->add($groupUser);
            $groupUser->setGroupId($this);
        }

        return $this;
    }

    public function removeGroupUser(GroupUsers $groupUser): self
    {
        if ($this->groupUsers->removeElement($groupUser)) {
            // set the owning side to null (unless already changed)
            if ($groupUser->getGroupId() === $this) {
                $groupUser->setGroupId(null);
            }
        }

        return $this;
    }
}
