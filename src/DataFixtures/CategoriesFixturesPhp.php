<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Categories;

class CategoriesFixturesPhp extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $parentPlats = $this->createCategories('Plats', null, $manager);
        $this->createCategories('Plats chaudes', $parentPlats, $manager);
        $this->createCategories('Plats froids', $parentPlats, $manager);

        $parentEntrees = $this->createCategories('Entrees', null, $manager);
        $this->createCategories('Entrees froids', $parentEntrees, $manager);
        $this->createCategories('Entrees chaudes', $parentEntrees, $manager);

        $parentDessert = $this->createCategories('Dessert', null, $manager);
        $parentBoissons = $this->createCategories('Boissons', null, $manager);




        $manager->flush();
    }

    public function createCategories(string $name, Categories $parent = null, ObjectManager $manager)
    {
        $category = new Categories();
        $category->setName($name);
        $category->setParent($parent);
        $manager->persist($category);

        return $category;
    }
}
