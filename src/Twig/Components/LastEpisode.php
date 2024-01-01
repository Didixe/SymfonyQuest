<?php

namespace App\Twig\Components;

use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent()]
final class LastEpisode
{
    public function __construct(
        private EpisodeRepository $episodeRepository,
        private ProgramRepository $programRepository,
        private SeasonRepository $seasonRepository
    ){
    }

    public function getLastEpisode(): array{

//        return $this->episodeRepository->findBy([], ['id' => 'DESC'], 3);

        return $this->episodeRepository->findLatestEpisode();

//        $lastEpisode = $this->episodeRepository->findLatestEpisode();
//        $lastEpisodes = [];
//
//        foreach ($lastEpisode as $episode) {
//            $season = $episode->getSeason();
//            $program = $season->getProgram();
//
//            $lastEpisodes[] = [
//                'episode' => [
//                    'title' => $episode->getTitle(),
//                    'number' => $episode->getNumber(),
//                ],
//                'season' => [
////                    'number' => $season->getNumber(),
//                    'number' => $episode->getSeason()->getNumber(),
//                ],
//                'program' => [
////                    'title' => $program->getTitle(),
//                    'title' => $episode->getSeason()->getProgram()->getTitle(),
//                ],
//            ];
//        }
//        return $lastEpisodes;
    }
}
