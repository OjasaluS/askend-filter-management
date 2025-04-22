<?php

namespace App\Controller;

use App\Entity\Filter;
use App\Entity\FilterSetting;
use App\Entity\Criteria;
use App\Entity\Comparator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/filters')]
class FilterController extends AbstractController
{
    #[Route('', name: 'filter_create', methods: ['OPTIONS', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        $filter = new Filter();
        $filter->setName($data['name']);

        foreach ($data['rules'] as $ruleData) {
            $criteria = $em->getRepository(Criteria::class)->find($ruleData['criteria_id']);
            $comparator = $em->getRepository(Comparator::class)->find($ruleData['comparator_id']);

            if (!$criteria || !$comparator) {
                return $this->json(['error' => 'Invalid criteria or comparator ID provided.'], 400);
            }

            $rule = new FilterSetting();
            $rule->setCriteria($criteria);
            $rule->setComparator($comparator);
            $rule->setValue($ruleData['value']);
            $rule->setFilter($filter);

            $em->persist($rule);
        }

        $em->persist($filter);
        $em->flush();

        return $this->json([
            'message' => 'Filter created successfully',
            'id' => $filter->getId()
        ], Response::HTTP_CREATED);
    }

    #[Route('', name: 'filter_index', methods: ['OPTIONS', 'GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $filters = $em->getRepository(Filter::class)->findAll();

        $data = array_map(function (Filter $filter) {
            return [
                'id' => $filter->getId(),
                'name' => $filter->getName(),
                'rules' => array_map(function (FilterSetting $rule) {
                    return [
                        'criteria' => $rule->getCriteria()?->getName(),
                        'comparator' => $rule->getComparator()?->getLabel(),
                        'value' => $rule->getValue(),
                    ];
                }, $filter->getRules()->toArray()),
            ];
        }, $filters);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'filter_show', methods: ['OPTIONS', 'GET'])]
    public function getOne(int $id, EntityManagerInterface $em): JsonResponse
    {
        $filter = $em->getRepository(Filter::class)->find($id);

        if (!$filter) {
            return $this->json(['error' => 'Filter not found'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $filter->getId(),
            'name' => $filter->getName(),
            'rules' => array_map(function (FilterSetting $rule) {
                return [
                    'criteria_id' => $rule->getCriteria()?->getId(),
                    'criteria' => $rule->getCriteria()?->getName(),
                    'comparator_id' => $rule->getComparator()?->getId(),
                    'comparator_key' => $rule->getComparator()?->getKey(),
                    'comparator_label' => $rule->getComparator()?->getLabel(),
                    'value' => $rule->getValue(),
                ];
            }, $filter->getRules()->toArray()),
        ];

        return $this->json($data);
    }

    #[Route('/{id}', name: 'filter_update', methods: ['OPTIONS', 'PUT', 'PATCH'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $filter = $em->getRepository(Filter::class)->find($id);

        if (!$filter) {
            return $this->json(['error' => 'Filter not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $filter->setName($data['name']);
        }

        if (isset($data['rules'])) {
            foreach ($filter->getRules() as $existingRule) {
                $em->remove($existingRule);
            }
            $filter->getRules()->clear();
            $em->flush();

            foreach ($data['rules'] as $ruleData) {
                $criteria = $em->getRepository(Criteria::class)->find($ruleData['criteria_id']);
                $comparator = $em->getRepository(Comparator::class)->find($ruleData['comparator_id']);

                if (!$criteria || !$comparator) {
                    return $this->json(['error' => 'Invalid criteria or comparator ID provided during update.'], 400);
                }

                $rule = new FilterSetting();
                $rule->setCriteria($criteria);
                $rule->setComparator($comparator);
                $rule->setValue($ruleData['value']);
                $rule->setFilter($filter);

                $em->persist($rule);
            }
        }

        $em->flush();

        return $this->json(['message' => 'Filter updated successfully', 'id' => $filter->getId()]);
    }


    #[Route('/{id}', name: 'filter_delete', methods: ['OPTIONS', 'DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $filter = $em->getRepository(Filter::class)->find($id);

        if (!$filter) {
            return $this->json(['error' => 'Filter not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($filter);
        $em->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
