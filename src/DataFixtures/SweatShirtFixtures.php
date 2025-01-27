<?php

namespace App\DataFixtures;

use App\Entity\SweatShirts;
use App\Entity\Size;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use League\Csv\Reader;

class SweatShirtFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Load Sizes first
        $sizeRepo = $manager->getRepository(Size::class);
        $csv = Reader::createFromPath(__DIR__.'/../../public/sweat_shirts.csv', 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            $size = $sizeRepo->find($record['size_id']);
            $sweatShirt = new SweatShirts();
            $sweatShirt->setName($record['name']);
            $sweatShirt->setSlug($record['slug']);
            $sweatShirt->setPrice($record['price']);
            $sweatShirt->setCreatedAt(new \DateTime($record['created_at']));
            $sweatShirt->setAttachment($record['attachment']);
            $sweatShirt->setSize($size);
            $manager->persist($sweatShirt);
        }

        $manager->flush();
    }
}