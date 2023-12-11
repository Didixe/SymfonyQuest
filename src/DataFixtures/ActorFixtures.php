<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use function Webmozart\Assert\Tests\StaticAnalysis\length;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $faker=Factory::create();

        for ($actorID = 0; $actorID <10; $actorID++){
            $actor = New Actor();
            $actor->setName($faker->name());

            for ($actorProgram = 0; $actorProgram <3; $actorProgram++) {

                $program = $this->getReference('program_' . rand(0, 4));
                $actor->addProgram($program);
            }
                $manager->persist($actor);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont SeasonFixtures dépend
        return [
            ProgramFixtures::class,
        ];
    }
}
