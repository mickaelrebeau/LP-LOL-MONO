<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[UniqueEntity('email')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $phone_number = null;

    // private ?string $plainPassword = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::SMALLINT /*default: 0*/)]
    private ?int $status = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $digicode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address_1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address_2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address_3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fix_number = null;

    #[ORM\Column(nullable: true)]
    private ?array $cb_1 = null;

    #[ORM\Column(nullable: true)]
    private ?array $cb_2 = null;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Token::class, orphanRemoval: true)]
    private Collection $tokens;

    #[ORM\OneToMany(mappedBy: 'user_requester', targetEntity: RequestAccess::class, orphanRemoval: true)]
    private Collection $requests;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: GroupUsers::class, orphanRemoval: true)]
    private Collection $groupUsers;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 45)]
    private ?string $username = null;

    public function __construct()
    {
        $this->tokens = new ArrayCollection();
        $this->requests = new ArrayCollection();
        $this->groupUsers = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    // public function getPlainPassword()
    // {
    //     return $this->plainPassword;
    // }

    // public function setPlainPassword($plainPassword)
    // {
    //     return $this->plainPassword;
    // }

    public function setPassword(?string $password): self
    {   
        if ($password !== null) {
            $this->password = $password;
        } else {
            throw new \InvalidArgumentException('Password cannot be null');
        }

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDigicode(): ?string
    {
        return $this->digicode;
    }

    public function setDigicode(?string $digicode): self
    {
        $this->digicode = $digicode;

        return $this;
    }

    public function getAddress_1(): ?string
    {
        return $this->address_1;
    }

    public function setAddress1(?string $address_1): self
    {
        $this->address_1 = $address_1;

        return $this;
    }

    public function getAddress_2(): ?string
    {
        return $this->address_2;
    }

    public function setAddress2(?string $address_2): self
    {
        $this->address_2 = $address_2;

        return $this;
    }

    public function getAddress_3(): ?string
    {
        return $this->address_3;
    }

    public function setAddress3(?string $address_3): self
    {
        $this->address_3 = $address_3;

        return $this;
    }

    public function getFix_Number(): ?string
    {
        return $this->fix_number;
    }

    public function setFixNumber(?string $fix_number): self
    {
        $this->fix_number = $fix_number;

        return $this;
    }

    public function getCb_1(): array
    {
        return $this->cb_1;
    }

    public function setCb1(?array $cb_1): self
    {
        $this->cb_1 = $cb_1;

        return $this;
    }

    public function getCb_2(): array
    {
        return $this->cb_2;
    }

    public function setCb2(?array $cb_2): self
    {
        $this->cb_2 = $cb_2;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Token>
     */
    public function getTokens(): Collection
    {
        return $this->tokens;
    }

    public function addToken(Token $token): self
    {
        if (!$this->tokens->contains($token)) {
            $this->tokens->add($token);
            $token->setUserId($this);
        }

        return $this;
    }

    public function removeToken(Token $token): self
    {
        if ($this->tokens->removeElement($token)) {
            // set the owning side to null (unless already changed)
            if ($token->getUserId() === $this) {
                $token->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RequestAccess>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(RequestAccess $request): self
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setUserRequester($this);
        }

        return $this;
    }

    public function removeRequest(RequestAccess $request): self
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getUserRequester() === $this) {
                $request->setUserRequester(null);
            }
        }

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
            $groupUser->setUserId($this);
        }

        return $this;
    }

    public function removeGroupUser(GroupUsers $groupUser): self
    {
        if ($this->groupUsers->removeElement($groupUser)) {
            // set the owning side to null (unless already changed)
            if ($groupUser->getUserId() === $this) {
                $groupUser->setUserId(null);
            }
        }

        return $this;
    }


    /**
     * Returning a salt is only needed if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    

    // Implement the required methods from Serializable

    public function serialize()
    {
        return serialize([$this->id, $this->email, $this->password]);
    }

    public function unserialize($serialized)
    {
        [$this->id, $this->email, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
}
