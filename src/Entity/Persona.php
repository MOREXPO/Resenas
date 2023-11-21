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

    #[ORM\ManyToMany(targetEntity: MedioPersona::class, mappedBy: 'persona')]
    private Collection $medioPersonas;

    public function __construct()
    {
        $this->etiquetas = new ArrayCollection();
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
            $medioPersona->addPersona($this);
        }

        return $this;
    }

    public function removeMedioPersona(MedioPersona $medioPersona): self
    {
        if ($this->medioPersonas->removeElement($medioPersona)) {
            $medioPersona->removePersona($this);
        }

        return $this;
    }
}
