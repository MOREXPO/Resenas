<?php

namespace App\Entity;

use App\Repository\ValoracionRepository;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ValoracionRepository::class)]
#[ApiResource]
class Valoracion
{
    #[Groups(['audiovisual:read','ia:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['audiovisual:read','ia:read'])]
    #[ORM\Column(nullable: true)]
    private ?float $calificacion = null;

    #[Groups(['ia:read'])]
    #[ORM\ManyToOne(inversedBy: 'valoraciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Resena $resena = null;

    #[Groups(['audiovisual:read'])]
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
