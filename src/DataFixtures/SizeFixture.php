<?php

namespace App\DataFixtures;

use App\Entity\Size;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use League\Csv\Reader;

class SizeFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $csv = Reader::createFromPath(__DIR__.'/../../public/size.csv', 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            $size = new Size();
            $size->setName($record['name']);
            $size->setSlug($record['slug']);
           
            $manager->persist($size);
        }

        $manager->flush();
    }
}