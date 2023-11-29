<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const Program = [
        [
            'title'=>'The Last Of Us',
            'synopsis'=>"L'humanité a été décimée à la suite de la mutation d'un champignon parasite, le Cordyceps. Vingt ans après le début de cette pandémie, Joel (Pedro Pascal), Ellie (Bella Ramsey) et Tess (Anna Torv), un trio lié par la dureté du monde dans lequel ils vivent, se lancent dans un périple à travers ce qu'il reste des États-Unis. Au cours de leur voyage, ils devront faire face aussi bien aux infectés qu'à des survivants hostiles.",
            'category'=>'category_Horreur'
        ],
        [
            'title'=>'Arcane',
            'synopsis'=>"Au milieu du conflit entre les villes jumelles de Piltover et Zaun, deux soeurs se battent dans les camps opposés d'une guerre entre technologies magiques et convictions incompatibles.",
            'category'=>'category_Animation'
        ],
        [
            'title'=>'Alice in Borderland',
            'synopsis'=>"Arisu, un obsédé de jeux vidéos, se retrouve soudainement dans une étrange version, totalement déserte, de Tokyo, et dans laquelle lui et ses amis doivent participer à des jeux dangereux pour survivre.",
            'category'=>'category_Action'
        ],
        [
            'title'=>'One Piece',
            'synopsis'=>"Dans un monde rempli de pirates, Monkey D. Luffy, accompagné de son nouvel équipage, part à l'aventure pour retrouver un mystérieux trésor légendaire et ainsi devenir le nouveau Roi des Pirates.",
            'category'=>'category_Aventure'
        ],
        [
            'title'=>'Mercredi',
            'synopsis'=>"A présent étudiante à la singulière Nevermore Academy, un pensionnat prestigieux pour parias, Wednesday Addams tente de s'adapter auprès des autres élèves tout en enquêtant sur une série de meurtres qui terrorise la ville.",
            'category'=>'category_Fantastique'
        ],
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::Program as $programData) {
            $program = new Program();
            $program->setTitle($programData['title']);
            $program->setSynopsis($programData['synopsis']);
            $program->setCategory($this->getReference($programData['category']));
            $manager->persist($program);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            CategoryFixtures::class,
        ];
    }
}
