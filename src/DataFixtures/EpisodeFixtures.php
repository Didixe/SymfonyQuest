<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        //src/DataFixtures/EpisodeFixtures.php
        $episode = new Episode();
        $episode->setTitle('Welcome to the Playground');
        $episode->setNumber(1);
        $episode->setSynopsis('intro Arcane');
        //... set other episode's properties
        //... create 2 more episodes
        $episode->setSeason($this->getReference('season1_Arcane'));

        $manager->persist($episode);
        $manager->flush();

        $this->addReference('episole1_Arcane',$episode);

    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont SeasonFixtures dépend
        return [
            SeasonFixtures::class,
        ];
    }

}