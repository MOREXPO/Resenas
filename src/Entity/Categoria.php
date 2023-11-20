<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriaRepository::class)]
class Categoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\ManyToMany(targetEntity: Pagina::class, mappedBy: 'categorias')]
    private Collection $paginas;

    public function __construct()
    {
        $this->paginas = new ArrayCollection();
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
     * @return Collection<int, Pagina>
     */
    public function getPaginas(): Collection
    {
        return $this->paginas;
    }

    public function addPagina(Pagina $pagina): self
    {
        if (!$this->paginas->contains($pagina)) {
            $this->paginas->add($pagina);
            $pagina->addCategoria($this);
        }

        return $this;
    }

    public function removePagina(Pagina $pagina): self
    {
        if ($this->paginas->removeElement($pagina)) {
            $pagina->removeCategoria($this);
        }

        return $this;
    }
}
