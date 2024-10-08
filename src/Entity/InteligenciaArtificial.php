<?php

namespace App\Entity;

use App\Repository\InteligenciaArtificialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: InteligenciaArtificialRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['ia:read']])]
class InteligenciaArtificial
{
    #[Groups(['ia:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[Groups(['ia:read'])]
    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[Groups(['ia:read'])]
    #[ORM\OneToMany(mappedBy: 'inteligenciaArtificial', targetEntity: Valoracion::class)]
    private Collection $valoraciones;

    public function __construct()
    {
        $this->valoraciones = new ArrayCollection();
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
            $valoracione->setInteligenciaArtificial($this);
        }

        return $this;
    }

    public function removeValoracione(Valoracion $valoracione): self
    {
        if ($this->valoraciones->removeElement($valoracione)) {
            // set the owning side to null (unless already changed)
            if ($valoracione->getInteligenciaArtificial() === $this) {
                $valoracione->setInteligenciaArtificial(null);
            }
        }

        return $this;
    }
}
