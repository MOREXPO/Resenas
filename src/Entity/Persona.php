<?php

namespace App\Entity;

use App\Repository\PersonaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonaRepository::class)]
#[ApiResource]
class Persona
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $nacionalidad = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaNacimiento = null;

    #[ORM\ManyToMany(targetEntity: Etiqueta::class, inversedBy: 'personas')]
    private Collection $etiquetas;

    #[ORM\OneToMany(mappedBy: 'persona', targetEntity: MedioPersonaEtiqueta::class)]
    private Collection $medio_persona_etiquetas;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $resumen = null;

    public function __construct()
    {
        $this->etiquetas = new ArrayCollection();
        $this->medio_persona_etiquetas = new ArrayCollection();
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

    public function getNacionalidad(): ?string
    {
        return $this->nacionalidad;
    }

    public function setNacionalidad(string $nacionalidad): self
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(\DateTimeInterface $fechaNacimiento): self
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * @return Collection<int, Etiqueta>
     */
    public function getEtiquetas(): Collection
    {
        return $this->etiquetas;
    }

    public function addEtiqueta(Etiqueta $etiqueta): self
    {
        if (!$this->etiquetas->contains($etiqueta)) {
            $this->etiquetas->add($etiqueta);
        }

        return $this;
    }

    public function removeEtiqueta(Etiqueta $etiqueta): self
    {
        $this->etiquetas->removeElement($etiqueta);

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
            $medio_persona_etiqueta->setEtiqueta($this);
        }

        return $this;
    }

    public function removeMedioPersonaEtiqueta(MedioPersonaEtiqueta $medio_persona_etiqueta): self
    {
        if ($this->medio_persona_etiquetas->removeElement($medio_persona_etiqueta)) {
            // set the owning side to null (unless already changed)
            if ($medio_persona_etiqueta->getEtiqueta() === $this) {
                $medio_persona_etiqueta->setEtiqueta(null);
            }
        }

        return $this;
    }

    public function getResumen(): ?string
    {
        return $this->resumen;
    }

    public function setResumen(?string $resumen): self
    {
        $this->resumen = $resumen;

        return $this;
    }
}
