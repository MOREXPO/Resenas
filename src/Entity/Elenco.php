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
    private ?Audiovisual $audiovisual = null;
    
    #[ORM\ManyToOne(inversedBy: 'elencos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Persona $persona = null;
    
    #[ORM\ManyToOne(inversedBy: 'elencos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etiqueta $etiqueta = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAudiovisual(): ?Audiovisual
    {
        return $this->audiovisual;
    }

    public function setAudiovisual(?Audiovisual $audiovisual): self
    {
        $this->audiovisual = $audiovisual;

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
