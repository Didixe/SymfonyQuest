<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger){
        $this->slugger=$slugger;
    }

    public function load(ObjectManager $manager):void
    {
        $faker=Factory::create();

        for($programNumber = 0; $programNumber < 5; $programNumber++) {
            for ($seasonNumber = 1; $seasonNumber <= 5; $seasonNumber++) {
                for ($episodeNumber = 1; $episodeNumber <= 10; $episodeNumber++) {
                    $episode = new Episode();
                    $episode->setTitle($faker->sentence);
                    $slug = $this->slugger->slug($episode->getTitle());
                    $episode->setSlug($slug);
//                    $hours = $faker->numberBetween(0, 3);
                    $minutes = $faker->numberBetween(20, 90);
                    $episode->setDuration($minutes);

                    $episode->setNumber($episodeNumber);
                    $episode->setSynopsis($faker->paragraphs(3, true));
                    $episode->setSeason($this->getReference('program_' . $programNumber . 'season_' . $seasonNumber));
                    $manager->persist($episode);
                }
            }
        }

        $manager->flush();

    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont SeasonFixtures dépend
        return [
            SeasonFixtures::class,
        ];
    }

}