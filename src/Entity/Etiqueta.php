<?php

namespace App\Entity;

use App\Repository\EtiquetaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtiquetaRepository::class)]
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

    #[ORM\ManyToMany(targetEntity: MedioPersona::class, mappedBy: 'etiquetas')]
    private Collection $medioPersonas;

    public function __construct()
    {
        $this->personas = new ArrayCollection();
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
            $medioPersona->addEtiqueta($this);
        }

        return $this;
    }

    public function removeMedioPersona(MedioPersona $medioPersona): self
    {
        if ($this->medioPersonas->removeElement($medioPersona)) {
            $medioPersona->removeEtiqueta($this);
        }

        return $this;
    }
}
