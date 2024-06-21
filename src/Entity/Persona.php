<?php

namespace App\Entity;

use App\Repository\PersonaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PersonaRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['persona:read']])]
#[ApiFilter(SearchFilter::class, properties: ['nombre' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'nombre', 'duracion'], arguments: ['orderParameterName' => 'order'])]
class Persona
{
    #[Groups(['audiovisual:read','persona:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['audiovisual:read','persona:read'])]
    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[Groups(['audiovisual:read','persona:read'])]
    #[ORM\Column(length: 255)]
    private ?string $imagen = null;

    #[Groups(['audiovisual:read','persona:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nacionalidad = null;

    #[Groups(['audiovisual:read','persona:read'])]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaNacimiento = null;

    #[Groups(['audiovisual:read','persona:read'])]
    #[ORM\ManyToMany(targetEntity: Etiqueta::class, inversedBy: 'personas')]
    private Collection $etiquetas;

    #[Groups(['persona:read','persona:read'])]
    #[ORM\OneToMany(mappedBy: 'persona', targetEntity: Elenco::class)]
    private Collection $elencos;

    #[Groups(['audiovisual:read','persona:read'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $resumen = null;

    public function __construct()
    {
        $this->etiquetas = new ArrayCollection();
        $this->elencos = new ArrayCollection();
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

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getNacionalidad(): ?string
    {
        return $this->nacionalidad;
    }

    public function setNacionalidad(string $nacionalidad): self
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(\DateTimeInterface $fechaNacimiento): self
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * @return Collection<int, Etiqueta>
     */
    public function getEtiquetas(): Collection
    {
        return $this->etiquetas;
    }

    public function addEtiqueta(Etiqueta $etiqueta): self
    {
        if (!$this->etiquetas->contains($etiqueta)) {
            $this->etiquetas->add($etiqueta);
        }

        return $this;
    }

    public function removeEtiqueta(Etiqueta $etiqueta): self
    {
        $this->etiquetas->removeElement($etiqueta);

        return $this;
    }

    /**
     * @return Collection<int, Elenco>
     */
    public function getElencos(): Collection
    {
        return $this->elencos;
    }

    public function addElenco(Elenco $elenco): self
    {
        if (!$this->elencos->contains($elenco)) {
            $this->elencos->add($elenco);
            $elenco->setEtiqueta($this);
        }

        return $this;
    }

    public function removeElenco(Elenco $elenco): self
    {
        if ($this->elencos->removeElement($elenco)) {
            // set the owning side to null (unless already changed)
            if ($elenco->getEtiqueta() === $this) {
                $elenco->setEtiqueta(null);
            }
        }

        return $this;
    }

    public function getResumen(): ?string
    {
        return $this->resumen;
    }

    public function setResumen(?string $resumen): self
    {
        $this->resumen = $resumen;

        return $this;
    }
}
