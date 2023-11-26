<?php

namespace App\Entity;

use App\Repository\ElencoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ElencoRepository::class)]
#[ApiResource]
class Elenco
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'elencos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Medio $medio = null;

    #[ORM\ManyToMany(targetEntity: Persona::class, inversedBy: 'elencos')]
    private Collection $persona;

    #[ORM\ManyToOne(inversedBy: 'elencos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etiqueta $etiqueta = null;

    public function __construct()
    {
        $this->persona = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEtiqueta(): ?Etiqueta
    {
        return $this->etiqueta;
    }

    public function setEtiqueta(?Etiqueta $etiqueta): self
    {
        $this->etiqueta = $etiqueta;

        return $this;
    }
}
