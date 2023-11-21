<?php

namespace App\Entity;

use App\Repository\MedioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedioRepository::class)]
#[ApiResource]
class Medio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?int $anoLanzamiento = null;

    #[ORM\OneToMany(mappedBy: 'medio', targetEntity: Resena::class)]
    private Collection $resenas;

    #[ORM\ManyToMany(targetEntity: MedioPersona::class, mappedBy: 'medio')]
    private Collection $medioPersonas;

    #[ORM\Column]
    private ?int $duracion = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $sinopsis = null;

    #[ORM\Column]
    private ?bool $pelicula = null;

    public function __construct()
    {
        $this->resenas = new ArrayCollection();
        $this->medioPersonas = new ArrayCollection();
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

    public function getAnoLanzamiento(): ?int
    {
        return $this->anoLanzamiento;
    }

    public function setAnoLanzamiento(int $anoLanzamiento): self
    {
        $this->anoLanzamiento = $anoLanzamiento;

        return $this;
    }

    /**
     * @return Collection<int, Resena>
     */
    public function getResenas(): Collection
    {
        return $this->resenas;
    }

    public function addResena(Resena $resena): self
    {
        if (!$this->resenas->contains($resena)) {
            $this->resenas->add($resena);
            $resena->setMedio($this);
        }

        return $this;
    }

    public function removeResena(Resena $resena): self
    {
        if ($this->resenas->removeElement($resena)) {
            // set the owning side to null (unless already changed)
            if ($resena->getMedio() === $this) {
                $resena->setMedio(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MedioPersona>
     */
    public function getMedioPersonas(): Collection
    {
        return $this->medioPersonas;
    }

    public function addMedioPersona(MedioPersona $medioPersona): self
    {
        if (!$this->medioPersonas->contains($medioPersona)) {
            $this->medioPersonas->add($medioPersona);
            $medioPersona->addMedio($this);
        }

        return $this;
    }

    public function removeMedioPersona(MedioPersona $medioPersona): self
    {
        if ($this->medioPersonas->removeElement($medioPersona)) {
            $medioPersona->removeMedio($this);
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
}
