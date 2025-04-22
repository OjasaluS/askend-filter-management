<?php

namespace App\Entity;

use App\Repository\ComparatorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ComparatorRepository::class)]
class Comparator
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['criteria:read', 'comparator:read'])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Criteria::class, inversedBy: 'comparators')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['comparator:read'])]
    private Criteria $criteria;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(['criteria:read', 'comparator:read'])]
    private string $key;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['criteria:read', 'comparator:read'])]
    private string $label;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCriteria(): Criteria
    {
        return $this->criteria;
    }

    public function setCriteria(Criteria $criteria): void
    {
        $this->criteria = $criteria;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }
}
