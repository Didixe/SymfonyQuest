<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Form\ProgramType;
use App\Form\SeasonType;
use App\Service\ProgramDuration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/program', name: 'program_')]
Class ProgramController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs]
        );
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProgramRepository $programRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();

            $this->addFlash('success', 'La nouvelle série a été créé');

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{slug}', name: 'show')]
    public function show(Program $program, ProgramDuration $programDuration, SluggerInterface $slugger):Response
    {
        $slug = $slugger->slug($program->getTitle());
        $program->setSlug($slug);

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'programDuration' => $programDuration->calculate($program),
        ]);
    }

    #[Route('/{slug}/season/{number}', name: 'season_show')]
    public function showSeason( Program $program, Season $season, SluggerInterface $slugger): Response
    {
        $slug = $slugger->slug($program->getTitle());
        $program->setSlug($slug);
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

//    #[Route('/{slug}/season/{number}/episode/{episode}', name: 'episode_show')]
    #[Route('/{slug}/season/{number}/episode/{slugEpisode}', name: 'episode_show')]
    public function showEpisode(#[MapEntity(mapping : ['slug' => 'slug'])] Program $program,
                                #[MapEntity(mapping : ['number' => 'number'])] Season $season,
                                #[MapEntity(mapping : ['slugEpisode' => 'slug'])] Episode $episode,
    ): Response
    {
      //  $slug = $slugger->slug($program->getTitle());
       // $program->setSlug($slug);

//        $slugEpisode = $slugger->slug($episode->getTitle());
//        $episode->setSlug($slugEpisode);

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }

    #[Route('/edit/{slug}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $entityManager->flush();

            $this->addFlash('success', 'La série a été modifié');

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{slug}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Program $program, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $entityManager->remove($program);
            $entityManager->flush();
        }

        $this->addFlash('danger', 'La série a été supprimé');

        return $this->redirectToRoute('program_index' , [], Response::HTTP_SEE_OTHER);
    }

}
