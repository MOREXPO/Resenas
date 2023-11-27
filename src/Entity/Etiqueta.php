<?php

namespace App\Entity;

use App\Repository\EtiquetaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtiquetaRepository::class)]
#[ApiResource]
class Etiqueta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\ManyToMany(targetEntity: Persona::class, mappedBy: 'etiquetas')]
    private Collection $personas;

    #[ORM\OneToMany(mappedBy: 'etiqueta', targetEntity: MedioPersonaEtiqueta::class)]
    private Collection $medio_persona_etiquetas;

    public function __construct()
    {
        $this->personas = new ArrayCollection();
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

    /**
     * @return Collection<int, Persona>
     */
    public function getPersonas(): Collection
    {
        return $this->personas;
    }

    public function addPersona(Persona $persona): self
    {
        if (!$this->personas->contains($persona)) {
            $this->personas->add($persona);
            $persona->addEtiqueta($this);
        }

        return $this;
    }

    public function removePersona(Persona $persona): self
    {
        if ($this->personas->removeElement($persona)) {
            $persona->removeEtiqueta($this);
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
}
