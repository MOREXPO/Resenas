<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoriaRepository::class)]
#[ApiResource]
class Categoria
{
    #[Groups(['audiovisual:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['audiovisual:read'])]
    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\ManyToMany(targetEntity: Pagina::class, mappedBy: 'categorias')]
    private Collection $paginas;

    #[ORM\ManyToMany(targetEntity: Medio::class, mappedBy: 'categorias')]
    private Collection $medios;

    public function __construct()
    {
        $this->paginas = new ArrayCollection();
        $this->medios = new ArrayCollection();
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

    /**
     * @return Collection<int, Medio>
     */
    public function getMedios(): Collection
    {
        return $this->medios;
    }

    public function addMedio(Medio $medio): self
    {
        if (!$this->medios->contains($medio)) {
            $this->medios->add($medio);
            $medio->addCategoria($this);
        }

        return $this;
    }

    public function removeMedio(Medio $medio): self
    {
        if ($this->medios->removeElement($medio)) {
            $medio->removeCategoria($this);
        }

        return $this;
    }
}
