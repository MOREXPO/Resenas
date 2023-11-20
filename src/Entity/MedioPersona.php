<?php

namespace App\Entity;

use App\Repository\MedioPersonaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedioPersonaRepository::class)]
class MedioPersona
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Medio::class, inversedBy: 'medioPersonas')]
    private Collection $medio;

    #[ORM\ManyToMany(targetEntity: Persona::class, inversedBy: 'medioPersonas')]
    private Collection $persona;

    #[ORM\ManyToMany(targetEntity: Etiqueta::class, inversedBy: 'medioPersonas')]
    private Collection $etiquetas;

    public function __construct()
    {
        $this->medio = new ArrayCollection();
        $this->persona = new ArrayCollection();
        $this->etiquetas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Medio>
     */
    public function getMedio(): Collection
    {
        return $this->medio;
    }

    public function addMedio(Medio $medio): self
    {
        if (!$this->medio->contains($medio)) {
            $this->medio->add($medio);
        }

        return $this;
    }

    public function removeMedio(Medio $medio): self
    {
        $this->medio->removeElement($medio);

        return $this;
    }

    /**
     * @return Collection<int, Persona>
     */
    public function getPersona(): Collection
    {
        return $this->persona;
    }

    public function addPersona(Persona $persona): self
    {
        if (!$this->persona->contains($persona)) {
            $this->persona->add($persona);
        }

        return $this;
    }

    public function removePersona(Persona $persona): self
    {
        $this->persona->removeElement($persona);

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
}
