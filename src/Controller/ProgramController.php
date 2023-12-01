<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;

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

    #[Route('/{id}', name: 'show')]
//    public function show(int $id, ProgramRepository $programRepository):Response
    public function show(Program $program):Response
    {
//        $program = $programRepository->findOneBy(['id' => $id]);
//        // same as $program = $programRepository->find($id);
//
//        if (!$program) {
//            throw $this->createNotFoundException(
//                'No program with id : '.$id.' found in program\'s table.'
//            );
//        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    #[Route('/{program}/season/{season}', name: 'season_show')]
//    public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    public function showSeason( Program $program, Season $season): Response
    {
//        $program = $programRepository->findOneBy(['id' => $programId]);
//
//        if (!$program) {
//            throw $this->createNotFoundException(
//                'No program with id : ' . $programId . ' found in program\'s table.'
//            );
//        }

//        $season = $seasonRepository->findOneBy(['id' => $seasonId, 'program' => $program]);

//        if (!$season) {
//            throw $this->createNotFoundException(
//                'No Season not found for the given program.'
//            );
//        }

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    #[Route('/{program}/season/{season}/episode/{episode}', name: 'episode_show')]
    public function showEpisode( Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}
