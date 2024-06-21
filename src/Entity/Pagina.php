<?php

namespace App\Entity;

use App\Repository\PaginaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PaginaRepository::class)]
#[ApiResource]
class Pagina
{
    #[Groups(['ia:read','audiovisual:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['ia:read','audiovisual:read'])]
    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $dominio = null;

    #[ORM\ManyToMany(targetEntity: Categoria::class, inversedBy: 'paginas')]
    private Collection $categorias;

    #[ORM\OneToMany(mappedBy: 'pagina', targetEntity: Resena::class, orphanRemoval: true)]
    private Collection $resenas;

    public function __construct()
    {
        $this->categorias = new ArrayCollection();
        $this->resenas = new ArrayCollection();
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

    public function getDominio(): ?string
    {
        return $this->dominio;
    }

    public function setDominio(string $dominio): self
    {
        $this->dominio = $dominio;

        return $this;
    }

    /**
     * @return Collection<int, Categoria>
     */
    public function getCategorias(): Collection
    {
        return $this->categorias;
    }

    public function addCategoria(Categoria $categoria): self
    {
        if (!$this->categorias->contains($categoria)) {
            $this->categorias->add($categoria);
        }

        return $this;
    }

    public function removeCategoria(Categoria $categoria): self
    {
        $this->categorias->removeElement($categoria);

        return $this;
    }

    /**
     * @return Collection<int, Resena>
     */
    public function getResenas(): Collection
    {
        return $this->resenas;
    }

    public function addResena(Resena $resena): self
    {
        if (!$this->resenas->contains($resena)) {
            $this->resenas->add($resena);
            $resena->setPagina($this);
        }

        return $this;
    }

    public function removeResena(Resena $resena): self
    {
        if ($this->resenas->removeElement($resena)) {
            // set the owning side to null (unless already changed)
            if ($resena->getPagina() === $this) {
                $resena->setPagina(null);
            }
        }

        return $this;
    }
}
