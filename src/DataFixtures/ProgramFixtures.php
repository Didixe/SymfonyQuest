<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger){
        $this->slugger=$slugger;
    }
    public function load(ObjectManager $manager): void
    {

        $program = new Program();
        $program->setTitle('Arcane');
        $slug = $this->slugger->slug($program->getTitle());
        $program->setSlug($slug);
        //https://www.allocine.fr/video/player_gen_cmedia=19594053&cserie=29127.html
        $program->setPoster('https://fr.web.img6.acsta.net/r_1920_1080/pictures/21/11/02/11/12/2878509.jpg');
        $program->setSynopsis("Au milieu du conflit entre les villes jumelles de Piltover et Zaun, deux soeurs se battent dans les camps opposés d'une guerre entre technologies magiques et convictions incompatibles.");
        $program->setCategory($this->getReference('category_Animation'));

        $program->setOwner($this->getReference('user_Contributor'));

        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_0', $program);

        $program = new Program();
        $program->setTitle('The Last Of Us');
        $slug = $this->slugger->slug($program->getTitle());
        $program->setSlug($slug);
        //https://www.allocine.fr/video/player_gen_cmedia=19599059&cserie=26316.html
        $program->setPoster('https://fr.web.img6.acsta.net/c_310_420/pictures/23/01/12/12/36/0727474.jpg');
        $program->setSynopsis("L'humanité a été décimée à la suite de la mutation d'un champignon parasite, le Cordyceps. Vingt ans après le début de cette pandémie, Joel (Pedro Pascal), Ellie (Bella Ramsey) et Tess (Anna Torv), un trio lié par la dureté du monde dans lequel ils vivent, se lancent dans un périple à travers ce qu'il reste des États-Unis. Au cours de leur voyage, ils devront faire face aussi bien aux infectés qu'à des survivants hostiles.");
        $program->setCategory($this->getReference('category_Horreur'));
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_1', $program);

        $program = new Program();
        $program->setTitle('Alice in Borderland');
        $slug = $this->slugger->slug($program->getTitle());
        $program->setSlug($slug);
        //https://www.allocine.fr/video/player_gen_cmedia=19590600&cserie=27439.html
        $program->setPoster('https://fr.web.img5.acsta.net/c_310_420/pictures/20/11/06/12/24/4584296.jpg');
        $program->setSynopsis("Arisu, un obsédé de jeux vidéos, se retrouve soudainement dans une étrange version, totalement déserte, de Tokyo, et dans laquelle lui et ses amis doivent participer à des jeux dangereux pour survivre.");
        $program->setCategory($this->getReference('category_Action'));
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_2', $program);

        $program = new Program();
        $program->setTitle('One Piece');
        $slug = $this->slugger->slug($program->getTitle());
        $program->setSlug($slug);
        //https://www.allocine.fr/video/player_gen_cmedia=19601915&cserie=22408.html
        $program->setPoster("https://fr.web.img4.acsta.net/c_310_420/pictures/23/08/01/16/46/1630654.jpg");
        $program->setSynopsis("Dans un monde rempli de pirates, Monkey D. Luffy, accompagné de son nouvel équipage, part à l'aventure pour retrouver un mystérieux trésor légendaire et ainsi devenir le nouveau Roi des Pirates.");
        $program->setCategory($this->getReference('category_Aventure'));
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_3', $program);

        $program = new Program();
        $program->setTitle('Mercredi');
        $slug = $this->slugger->slug($program->getTitle());
        $program->setSlug($slug);
        //https://www.allocine.fr/video/player_gen_cmedia=19598423&cserie=28487.html
        $program->setPoster('https://fr.web.img2.acsta.net/c_310_420/pictures/22/09/23/15/11/2942764.jpg');
        $program->setSynopsis("A présent étudiante à la singulière Nevermore Academy, un pensionnat prestigieux pour parias, Wednesday Addams tente de s'adapter auprès des autres élèves tout en enquêtant sur une série de meurtres qui terrorise la ville.");
        $program->setCategory($this->getReference('category_Fantastique'));
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_4', $program);

    }
    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
