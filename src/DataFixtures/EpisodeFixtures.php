<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager):void
    {
        $faker=Factory::create();

        for($programNumber = 0; $programNumber < 5; $programNumber++) {
            for ($seasonNumber = 1; $seasonNumber <= 5; $seasonNumber++) {
                for ($episodeNumber = 1; $episodeNumber <= 10; $episodeNumber++) {
                    $episode = new Episode();
                    $episode->setTitle($faker->sentence);
                    //            $episode->setNumber($faker->numberBetween(1, 10));
                    $episode->setNumber($episodeNumber);
                    $episode->setSynopsis($faker->paragraphs(3, true));
                    //            $episode->setSeason($this->getReference('season_' . $faker->numberBetween(0, 5)));
                    $episode->setSeason($this->getReference('program_' . $programNumber . 'season_' . $seasonNumber));
                    //            $episode->setSeason($this->getReference('season1_Arcane'));
                    $manager->persist($episode);
                }
            }
        }
        //src/DataFixtures/EpisodeFixtures.php
//        $episode = new Episode();
//        $episode->setTitle('Welcome to the Playground');
//        $episode->setNumber(1);
//        $episode->setSynopsis('intro Arcane');
//        //... set other episode's properties
//        //... create 2 more episodes
//        $episode->setSeason($this->getReference('season1_Arcane'));
//
//        $manager->persist($episode);
        $manager->flush();

//        $this->addReference('episole1_Arcane',$episode);

    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont SeasonFixtures dépend
        return [
            SeasonFixtures::class,
        ];
    }

}