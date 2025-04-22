<?php

namespace App\Controller;

use App\Repository\ComparatorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/comparators')]
class ComparatorController extends AbstractController
{
    private ComparatorRepository $comparatorRepository;

    public function __construct(ComparatorRepository $comparatorRepository)
    {
        $this->comparatorRepository = $comparatorRepository;
    }

    #[Route('', name: 'get_all_comparators', methods: ['GET'])]
    public function getAllComparators(): JsonResponse
    {
        $comparators = $this->comparatorRepository->findAll();
        return $this->json($comparators, Response::HTTP_OK, [], ['groups' => 'comparator:read']);
    }

    #[Route('/{id}', name: 'get_comparator_by_id', methods: ['GET'])]
    public function getComparatorById(int $id): JsonResponse
    {
        $comparator = $this->comparatorRepository->find($id);

        if (!$comparator) {
            return $this->json(['error' => 'Comparator not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($comparator, Response::HTTP_OK, [], ['groups' => 'comparator:read']);
    }
}
