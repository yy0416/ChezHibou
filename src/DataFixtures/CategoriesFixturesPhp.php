<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Categories;

class CategoriesFixturesPhp extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $parent = new Categories();
        $parent->setName('Plats');
        $manager->persist($parent);


        $category = new Categories();
        $category->setName('Plats chaudes');
        $category->setParent($parent);
        $manager->persist($category);

        $manager->flush();
    }
}
