<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Blameable\Traits\BlameableEntity;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    use TimestampableEntity;
    use BlameableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Blague::class, orphanRemoval: true)]
    private Collection $blagues;

    public function __construct()
    {
        $this->blagues = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Blague>
     */
    public function getBlagues(): Collection
    {
        return $this->blagues;
    }

    public function addBlague(Blague $blague): static
    {
        if (!$this->blagues->contains($blague)) {
            $this->blagues->add($blague);
            $blague->setCategorie($this);
        }

        return $this;
    }

    public function removeBlague(Blague $blague): static
    {
        if ($this->blagues->removeElement($blague)) {
            // set the owning side to null (unless already changed)
            if ($blague->getCategorie() === $this) {
                $blague->setCategorie(null);
            }
        }

        return $this;
    }
}
