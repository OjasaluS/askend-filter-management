<?php

namespace App\Entity;

use App\Repository\CriteriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CriteriaRepository::class)]
class Criteria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['criteria:read', 'comparator:read'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(['criteria:read', 'comparator:read'])]
    private string $name;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['criteria:read', 'comparator:read'])]
    private string $type;

    #[ORM\OneToMany(mappedBy: 'criteria', targetEntity: Comparator::class)]
    #[Groups(['criteria:read'])]
    private Collection $comparators;

    public function __construct()
    {
        $this->comparators = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return Collection|Comparator[]
     */
    public function getComparators(): Collection
    {
        return $this->comparators;
    }
}
