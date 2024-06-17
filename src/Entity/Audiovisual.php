<?php

namespace App\Entity;

use App\Repository\AudiovisualRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AudiovisualRepository::class)]
#[ApiResource]
class Audiovisual
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\OneToMany(mappedBy: 'audiovisual', targetEntity: Elenco::class)]
    private Collection $elencos;

    #[ORM\Column(nullable: true)]
    private ?int $duracion = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $sinopsis = null;

    #[ORM\Column]
    private ?bool $pelicula = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaLanzamiento = null;

    #[ORM\ManyToOne(inversedBy: 'audiovisuals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Medio $medio = null;

    

    public function __construct()
    {
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
}
