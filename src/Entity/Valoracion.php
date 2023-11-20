<?php

namespace App\Entity;

use App\Repository\ValoracionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValoracionRepository::class)]
class Valoracion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $calificacion = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Resena $resena = null;

    #[ORM\ManyToOne(inversedBy: 'valoraciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?InteligenciaArtificial $inteligenciaArtificial = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCalificacion(): ?float
    {
        return $this->calificacion;
    }

    public function setCalificacion(float $calificacion): self
    {
        $this->calificacion = $calificacion;

        return $this;
    }

    public function getResena(): ?Resena
    {
        return $this->resena;
    }

    public function setResena(Resena $resena): self
    {
        $this->resena = $resena;

        return $this;
    }

    public function getInteligenciaArtificial(): ?InteligenciaArtificial
    {
        return $this->inteligenciaArtificial;
    }

    public function setInteligenciaArtificial(?InteligenciaArtificial $inteligenciaArtificial): self
    {
        $this->inteligenciaArtificial = $inteligenciaArtificial;

        return $this;
    }
}
