<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PhpDeveloperLevel extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Junior Level 1');
        $manager->persist($product);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Junior Level 2');
        $manager->persist($product);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Junior Level 3');
        $manager->persist($product);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Middle Level 1');
        $manager->persist($product);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Middle Level 2');
        $manager->persist($product);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Middle Level 3');
        $manager->persist($product);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Senior Level 1');
        $manager->persist($product);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Senior Level 2');
        $manager->persist($product);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Senior Level 3');
        $manager->persist($product);
        $manager->flush();
    }
}
