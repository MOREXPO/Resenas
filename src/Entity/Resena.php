<?php

namespace App\Entity;

use App\Repository\ResenaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ResenaRepository::class)]
#[ApiResource]
class Resena
{
    #[Groups(['audiovisual:read','ia:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['audiovisual:read'])]
    #[ORM\Column(length: 255)]
    private ?string $autor = null;

    #[Groups(['audiovisual:read'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $texto = null;

    #[Groups(['ia:read'])]
    #[ORM\ManyToOne(inversedBy: 'resenas')]
    private ?Medio $medio = null;

    #[Groups(['ia:read','audiovisual:read'])]
    #[ORM\ManyToOne(inversedBy: 'resenas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pagina $pagina = null;

    #[Groups(['audiovisual:read'])]
    #[ORM\OneToMany(mappedBy: 'resena', targetEntity: Valoracion::class)]
    private Collection $valoraciones;

    public function __construct()
    {
        $this->valoraciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAutor(): ?string
    {
        return $this->autor;
    }

    public function setAutor(string $autor): self
    {
        $this->autor = $autor;

        return $this;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(?string $texto): self
    {
        $this->texto = $texto;

        return $this;
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

    public function getPagina(): ?Pagina
    {
        return $this->pagina;
    }

    public function setPagina(?Pagina $pagina): self
    {
        $this->pagina = $pagina;

        return $this;
    }

    /**
     * @return Collection<int, Valoracion>
     */
    public function getValoraciones(): Collection
    {
        return $this->valoraciones;
    }

    public function addValoracione(Valoracion $valoracione): self
    {
        if (!$this->valoraciones->contains($valoracione)) {
            $this->valoraciones->add($valoracione);
            $valoracione->setResena($this);
        }

        return $this;
    }

    public function removeValoracione(Valoracion $valoracione): self
    {
        if ($this->valoraciones->removeElement($valoracione)) {
            // set the owning side to null (unless already changed)
            if ($valoracione->getResena() === $this) {
                $valoracione->setResena(null);
            }
        }

        return $this;
    }
}
