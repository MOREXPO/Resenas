<?php

namespace App\Entity;

use App\Repository\MedioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedioRepository::class)]
class Medio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $tipo = null;

    #[ORM\OneToMany(mappedBy: 'audiovisual', targetEntity: Resena::class)]
    private Collection $resenas;

    #[ORM\ManyToMany(targetEntity: Categoria::class, inversedBy: 'audiovisuals')]
    private Collection $categorias;

    #[ORM\OneToMany(mappedBy: 'medio', targetEntity: Audiovisual::class)]
    private Collection $audiovisuals;

    public function __construct()
    {
        $this->resenas = new ArrayCollection();
        $this->categorias = new ArrayCollection();
        $this->audiovisuals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): ?int
    {
        return $this->tipo;
    }

    public function setTipo(int $tipo): self
    {
        $this->tipo = $tipo;

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
            $resena->setAudiovisual($this);
        }

        return $this;
    }

    public function removeResena(Resena $resena): self
    {
        if ($this->resenas->removeElement($resena)) {
            // set the owning side to null (unless already changed)
            if ($resena->getAudiovisual() === $this) {
                $resena->setAudiovisual(null);
            }
        }

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
     * @return Collection<int, Audiovisual>
     */
    public function getAudiovisuals(): Collection
    {
        return $this->audiovisuals;
    }

    public function addAudiovisual(Audiovisual $audiovisual): self
    {
        if (!$this->audiovisuals->contains($audiovisual)) {
            $this->audiovisuals->add($audiovisual);
            $audiovisual->setMedio($this);
        }

        return $this;
    }

    public function removeAudiovisual(Audiovisual $audiovisual): self
    {
        if ($this->audiovisuals->removeElement($audiovisual)) {
            // set the owning side to null (unless already changed)
            if ($audiovisual->getMedio() === $this) {
                $audiovisual->setMedio(null);
            }
        }

        return $this;
    }
}
