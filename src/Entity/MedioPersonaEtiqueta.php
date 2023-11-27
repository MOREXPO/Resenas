<?php

namespace App\Entity;

use App\Repository\MedioPersonaEtiquetaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedioPersonaEtiquetaRepository::class)]
#[ApiResource]
class MedioPersonaEtiqueta
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'medio_persona_etiquetas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Medio $medio = null;
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'medio_persona_etiquetas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Persona $persona = null;
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'medio_persona_etiquetas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etiqueta $etiqueta = null;

    public function __construct()
    {
        $this->persona = new ArrayCollection();
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

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(?Persona $persona): self
    {
        $this->persona = $persona;

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
