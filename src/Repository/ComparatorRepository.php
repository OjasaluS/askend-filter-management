<?php

namespace App\Repository;

use App\Entity\Comparator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ComparatorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comparator::class);
    }

    public function findAllComparators(): array
    {
        return $this->findAll();
    }

    public function findComparatorById(int $id): ?Comparator
    {
        return $this->find($id);
    }
}
