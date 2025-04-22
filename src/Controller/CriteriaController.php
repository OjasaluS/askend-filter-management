<?php

namespace App\Controller;

use App\Repository\CriteriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/criteria')]
class CriteriaController extends AbstractController
{
    private CriteriaRepository $criteriaRepository;

    public function __construct(CriteriaRepository $criteriaRepository)
    {
        $this->criteriaRepository = $criteriaRepository;
    }

    #[Route('', name: 'get_all_criteria', methods: ['GET'])]
    public function getAllCriteria(): JsonResponse
    {
        $criteria = $this->criteriaRepository->findAll();
        return $this->json($criteria, Response::HTTP_OK, [], ['groups' => 'criteria:read']);
    }

    #[Route('/{id}', name: 'get_criteria_by_id', methods: ['GET'])]
    public function getCriteriaById(int $id): JsonResponse
    {
        $criteria = $this->criteriaRepository->find($id);

        if (!$criteria) {
            return new JsonResponse(['error' => 'Criteria not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($criteria, Response::HTTP_OK, [], ['groups' => 'criteria:read']);
    }
}
