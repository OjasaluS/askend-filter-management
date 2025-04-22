<?php

namespace App\Entity;

use App\Repository\FilterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilterRepository::class)]
class Filter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'filter', targetEntity: FilterSetting::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $rules;

    public function __construct()
    {
        $this->rules = new ArrayCollection();
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

    /**
     * @return Collection<int, FilterSetting>
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function addRule(FilterSetting $rule): void
    {
        if (!$this->rules->contains($rule)) {
            $this->rules[] = $rule;
            $rule->setFilter($this);
        }
    }

    public function removeRule(FilterSetting $rule): void
    {
        if ($this->rules->removeElement($rule)) {
            if ($rule->getFilter() === $this) {
                $rule->setFilter(null);
            }
        }
    }
}
