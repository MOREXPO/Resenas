<?php

namespace App\Entity;

use App\Repository\ConfigurationPaginaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigurationPaginaRepository::class)]
class ConfigurationPagina
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Pagina $pagina = null;

    #[ORM\Column(length: 255)]
    private ?string $campo = null;

    #[ORM\Column(nullable: true)]
    private ?int $numero = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $texto = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCampo(): ?string
    {
        return $this->campo;
    }

    public function setCampo(string $campo): self
    {
        $this->campo = $campo;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

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
}
