<?php

namespace App\Entity;

use App\Repository\FilterSettingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilterSettingRepository::class)]
class FilterSetting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Filter::class, inversedBy: 'rules')]
    #[ORM\JoinColumn(nullable: false)]
    private Filter $filter;

    #[ORM\ManyToOne(targetEntity: Criteria::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Criteria $criteria;

    #[ORM\ManyToOne(targetEntity: Comparator::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Comparator $comparator;

    #[ORM\Column(type: 'string', length: 255)]
    private string $value;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFilter(): Filter
    {
        return $this->filter;
    }

    public function setFilter(Filter $filter): void
    {
        $this->filter = $filter;
    }

    public function getCriteria(): Criteria
    {
        return $this->criteria;
    }

    public function setCriteria(Criteria $criteria): void
    {
        $this->criteria = $criteria;
    }

    public function getComparator(): Comparator
    {
        return $this->comparator;
    }

    public function setComparator(Comparator $comparator): void
    {
        $this->comparator = $comparator;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
