<?php

namespace App\Repository;

use App\Entity\Criteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CriteriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Criteria::class);
    }

    public function findAllCriteria(): array
    {
        return $this->findAll();
    }

    public function findCriteriaById(int $id): ?Criteria
    {
        return $this->find($id);
    }
}
