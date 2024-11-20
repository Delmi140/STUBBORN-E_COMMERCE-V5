<?php

namespace App\Entity;

use App\Repository\SizeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SizeRepository::class)]
class Size
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?int $stock = null;

    /**
     * @var Collection<int, SweatShirts>
     */
    #[ORM\OneToMany(targetEntity: SweatShirts::class, mappedBy: 'size')]
    private Collection $sweatShirts;

    public function __toString()
    {
        return $this->name;
    }


    public function __construct()
    {
        $this->sweatShirts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection<int, SweatShirts>
     */
    public function getSweatShirts(): Collection
    {
        return $this->sweatShirts;
    }

    public function addSweatShirt(SweatShirts $sweatShirt): static
    {
        if (!$this->sweatShirts->contains($sweatShirt)) {
            $this->sweatShirts->add($sweatShirt);
            $sweatShirt->setSize($this);
        }

        return $this;
    }

    public function removeSweatShirt(SweatShirts $sweatShirt): static
    {
        if ($this->sweatShirts->removeElement($sweatShirt)) {
            // set the owning side to null (unless already changed)
            if ($sweatShirt->getSize() === $this) {
                $sweatShirt->setSize(null);
            }
        }

        return $this;
    }
}
