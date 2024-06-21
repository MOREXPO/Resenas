<?php

namespace App\Entity;

use App\Controller\AudiovisualsMediasController;
use App\Repository\AudiovisualRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;

#[ORM\Entity(repositoryClass: AudiovisualRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            controller: AudiovisualsMediasController::class,
            uriTemplate: '/audiovisuals/medias/{page}/{orderBy}',
            name: 'audiovisuals_medias',
            read: false
        ),
        new GetCollection(),
        new Put(),
        new Post(),
        new Delete(),
        new Get(),
    ],
    normalizationContext: ['groups' => ['audiovisual:read']]
)]
#[ApiFilter(SearchFilter::class, properties: ['nombre' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'nombre', 'fechaLanzamiento', 'duracion'], arguments: ['orderParameterName' => 'order'])]
class Audiovisual
{
    #[Groups(['audiovisual:read', 'ia:read', 'user:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['audiovisual:read', 'ia:read'])]
    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[Groups(['audiovisual:read'])]
    #[ORM\Column(length: 255)]
    private ?string $imagen = null;

    #[Groups(['audiovisual:read'])]
    #[ORM\OneToMany(mappedBy: 'audiovisual', targetEntity: Elenco::class)]
    private Collection $elencos;

    #[Groups(['audiovisual:read'])]
    #[ORM\Column(nullable: true)]
    private ?int $duracion = null;

    #[Groups(['audiovisual:read'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $sinopsis = null;

    #[Groups(['audiovisual:read'])]
    #[ORM\Column]
    private ?bool $pelicula = null;

    #[Groups(['audiovisual:read'])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaLanzamiento = null;

    #[Groups(['audiovisual:read'])]
    #[ORM\ManyToOne(inversedBy: 'audiovisuals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Medio $medio = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'audiovisuals', cascade: ['persist'])]
    private Collection $users;



    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->elencos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * @return Collection<int, Elenco>
     */
    public function getElencos(): Collection
    {
        return $this->elencos;
    }

    public function addElenco(Elenco $elenco): self
    {
        if (!$this->elencos->contains($elenco)) {
            $this->elencos->add($elenco);
            $elenco->setAudiovisual($this);
        }

        return $this;
    }

    public function removeElenco(Elenco $elenco): self
    {
        if ($this->elencos->removeElement($elenco)) {
            // set the owning side to null (unless already changed)
            if ($elenco->getAudiovisual() === $this) {
                $elenco->setAudiovisual(null);
            }
        }

        return $this;
    }

    public function getDuracion(): ?int
    {
        return $this->duracion;
    }

    public function setDuracion(int $duracion): self
    {
        $this->duracion = $duracion;

        return $this;
    }

    public function getSinopsis(): ?string
    {
        return $this->sinopsis;
    }

    public function setSinopsis(string $sinopsis): self
    {
        $this->sinopsis = $sinopsis;

        return $this;
    }

    public function isPelicula(): ?bool
    {
        return $this->pelicula;
    }

    public function setPelicula(bool $pelicula): self
    {
        $this->pelicula = $pelicula;

        return $this;
    }

    public function getFechaLanzamiento(): ?\DateTimeInterface
    {
        return $this->fechaLanzamiento;
    }

    public function setFechaLanzamiento(\DateTimeInterface $fechaLanzamiento): self
    {
        $this->fechaLanzamiento = $fechaLanzamiento;

        return $this;
    }

    public function getMedio(): ?Medio
    {
        return $this->medio;
    }

    public function setMedio(?Medio $medio): self
    {
        $this->medio = $medio;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }
}
