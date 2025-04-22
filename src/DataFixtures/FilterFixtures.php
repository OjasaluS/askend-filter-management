<?php

namespace App\DataFixtures;

use App\Entity\Filter;
use App\Entity\FilterSetting;
use App\Entity\Criteria;
use App\Entity\Comparator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FilterFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $criteriaData = [
            ['name' => 'Amount', 'type' => 'number', 'comparators' => [
                ['key' => 'gt', 'label' => 'Greater than'],
                ['key' => 'lt', 'label' => 'Less than'],
                ['key' => 'eq', 'label' => 'Equal to'],
            ]],
            ['name' => 'Date', 'type' => 'date', 'comparators' => [
                ['key' => 'before', 'label' => 'Earlier than'],
                ['key' => 'after', 'label' => 'Later than'],
                ['key' => 'on', 'label' => 'Equal to'],
            ]],
            ['name' => 'Name', 'type' => 'string', 'comparators' => [
                ['key' => 'starts', 'label' => 'Starts with'],
                ['key' => 'ends', 'label' => 'Ends with'],
                ['key' => 'contains', 'label' => 'Contains'],
            ]],
        ];

        $criteriaRefs = [];
        $comparatorRefs = [];

        foreach ($criteriaData as $critData) {
            $criteria = new Criteria();
            $criteria->setName($critData['name']);
            $criteria->setType($critData['type']);
            $manager->persist($criteria);

            $criteriaRefs[$critData['name']] = $criteria;

            foreach ($critData['comparators'] as $compData) {
                $comparator = new Comparator();
                $comparator->setCriteria($criteria);
                $comparator->setKey($compData['key']);
                $comparator->setLabel($compData['label']);
                $manager->persist($comparator);

                $comparatorRefs[$compData['key']] = $comparator;
            }
        }

        $filter1 = new Filter();
        $filter1->setName('High Amount Orders');
        $manager->persist($filter1);

        $setting1 = new FilterSetting();
        $setting1->setFilter($filter1);
        $setting1->setCriteria($criteriaRefs['Amount']);
        $setting1->setComparator($comparatorRefs['gt']);
        $setting1->setValue('1000');
        $manager->persist($setting1);

        $filter2 = new Filter();
        $filter2->setName('Recent Orders');
        $manager->persist($filter2);

        $setting2 = new FilterSetting();
        $setting2->setFilter($filter2);
        $setting2->setCriteria($criteriaRefs['Date']);
        $setting2->setComparator($comparatorRefs['after']);
        $setting2->setValue('2024-01-01');
        $manager->persist($setting2);

        $manager->flush();
    }
}

