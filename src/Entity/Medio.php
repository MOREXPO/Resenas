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

    #[ORM\OneToMany(mappedBy: 'medio', targetEntity: Resena::class)]
    private Collection $resenas;

    #[ORM\OneToMany(mappedBy: 'medio', targetEntity: MedioPersonaEtiqueta::class)]
    private Collection $medio_persona_etiquetas;

    #[ORM\Column]
    private ?int $duracion = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $sinopsis = null;

    #[ORM\Column]
    private ?bool $pelicula = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaLanzamiento = null;

    #[ORM\ManyToMany(targetEntity: Categoria::class, inversedBy: 'medios')]
    private Collection $categorias;

    public function __construct()
    {
        $this->resenas = new ArrayCollection();
        $this->medio_persona_etiquetas = new ArrayCollection();
        $this->categorias = new ArrayCollection();
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
     * @return Collection<int, MedioPersonaEtiqueta>
     */
    public function getMedioPersonaEtiquetas(): Collection
    {
        return $this->medio_persona_etiquetas;
    }

    public function addMedioPersonaEtiqueta(MedioPersonaEtiqueta $medio_persona_etiqueta): self
    {
        if (!$this->medio_persona_etiquetas->contains($medio_persona_etiqueta)) {
            $this->medio_persona_etiquetas->add($medio_persona_etiqueta);
            $medio_persona_etiqueta->setMedio($this);
        }

        return $this;
    }

    public function removeMedioPersonaEtiqueta(MedioPersonaEtiqueta $medio_persona_etiqueta): self
    {
        if ($this->medio_persona_etiquetas->removeElement($medio_persona_etiqueta)) {
            // set the owning side to null (unless already changed)
            if ($medio_persona_etiqueta->getMedio() === $this) {
                $medio_persona_etiqueta->setMedio(null);
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

    /**
     * @return Collection<int, Categoria>
     */
    public function getCategorias(): Collection
    {
        return $this->categorias;
    }

    public function addCategoria(Categoria $categoria): self
    {
        if (!$this->categorias->contains($categoria)) {
            $this->categorias->add($categoria);
        }

        return $this;
    }

    public function removeCategoria(Categoria $categoria): self
    {
        $this->categorias->removeElement($categoria);

        return $this;
    }
}
