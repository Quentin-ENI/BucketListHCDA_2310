<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WishFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Utilisation de FakerPHP
        $faker = \Faker\Factory::create('fr_FR');

        $references = [ "travel", "sport", "entertainment", "human-relations", "others"];

        for ($i = 0; $i < 10; $i++) {
            $wish = new Wish();
            $wish->setTitle($faker->sentence(10))
                ->setDescription($faker->sentence)
                ->setAuthor($faker->name)
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')))
                ->setPublished($faker->boolean)
                ->setCategory($this->getReference(
                    $references[array_rand($references)]
                ));
            $manager->persist($wish);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class
        ];
    }
}
