<?php

namespace App\Entity;

use App\Controller\AddAudiovisualController;
use App\Controller\UserAuthController;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Controller\RegistrationController;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new Get(security: "is_granted('ROLE_USER')"),
        new Get(
            controller: UserAuthController::class,
            uriTemplate: '/user/auth',
            name: 'user_auth',
            read: false,
            security: "is_granted('ROLE_USER')",
        ),
        new Put(
            security: "is_granted('ROLE_USER') and object.getId() == user.getId()"
        ),
        new Put(
            controller: AddAudiovisualController::class,
            uriTemplate: '/user/add/{id}',
            name: 'user_add_audiovisual',
            read: false,
            security: "is_granted('ROLE_USER')"
        ),
        new Delete(
            security: "is_granted('ROLE_USER') and object.getId() == user.getId()"
        ),
        new Post(security: "is_granted('ROLE_USER')"),
        new Post(
            controller: RegistrationController::class,
            uriTemplate: '/registration',
            name: 'api_registration'
        ),
        new GetCollection(),
    ],
    normalizationContext: ['groups' => ['user:read']]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(['user:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['user:read'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[Groups(['user:read'])]
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Groups(['user:read'])]
    #[ORM\ManyToMany(targetEntity: Audiovisual::class, mappedBy: 'users')]
    private Collection $audiovisuals;

    public function __construct()
    {
        $this->audiovisuals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Audiovisual>
     */
    public function getAudiovisuals(): Collection
    {
        return $this->audiovisuals;
    }

    public function addAudiovisual(Audiovisual $audiovisual): self
    {
        if (!$this->audiovisuals->contains($audiovisual)) {
            $this->audiovisuals->add($audiovisual);
            $audiovisual->addUser($this);
        }

        return $this;
    }

    public function removeAudiovisual(Audiovisual $audiovisual): self
    {
        if ($this->audiovisuals->removeElement($audiovisual)) {
            $audiovisual->removeUser($this);
        }
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
}
