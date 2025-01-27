<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use League\Csv\Reader;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $csv = Reader::createFromPath(__DIR__.'/../../public/user.csv', 'r');
        $csv->setHeaderOffset(0); // Skip the header

        foreach ($csv as $record) {
            $user = new User();
            $user->setEmail($record['email']);
            $user->setRoles(json_decode($record['roles']));
            $user->setPassword($record['password']);
            $user->setName($record['name']);
            $user->setDeliveryAddress($record['delivery_address']);
            $user->setCreatedAt(new \DateTime($record['created_at']));
            $user->setVerified($record['is_verified']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}