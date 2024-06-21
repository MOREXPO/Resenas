<?php

namespace App\Entity;

use App\Repository\EtiquetaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EtiquetaRepository::class)]
#[ApiResource]
class Etiqueta
{
    #[Groups(['audiovisual:read','persona:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['audiovisual:read','persona:read'])]
    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\ManyToMany(targetEntity: Persona::class, mappedBy: 'etiquetas')]
    private Collection $personas;

    #[ORM\OneToMany(mappedBy: 'etiqueta', targetEntity: Elenco::class)]
    private Collection $elencos;

    public function __construct()
    {
        $this->personas = new ArrayCollection();
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
            $elenco->setEtiqueta($this);
        }

        return $this;
    }

    public function removeElenco(Elenco $elenco): self
    {
        if ($this->elencos->removeElement($elenco)) {
            // set the owning side to null (unless already changed)
            if ($elenco->getEtiqueta() === $this) {
                $elenco->setEtiqueta(null);
            }
        }

        return $this;
    }
}
