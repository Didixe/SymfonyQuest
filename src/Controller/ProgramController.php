<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\ProgramType;
use App\Repository\CommentRepository;
use App\Service\ProgramDuration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Entity\User;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
//use Symfony\Component\Validator\Constraints\Email;


#[Route('/program', name: 'program_')]
Class ProgramController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {

//        if ($this->getUser()) {
//            // Récupère les dernières séries en utilisant la méthode du repository
//            $latestPrograms = $programRepository->findLatestPrograms();
//
//            // Passe les séries à la vue de la page d'accueil
//            return $this->render('index.html.twig', [
//                'latestPrograms' => $latestPrograms,
//            ]);
//        }

        $programs = $programRepository->findAll();

        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs]
        );
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProgramRepository $programRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $program->setOwner($this->getUser());
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();

            $this->addFlash('success', 'La nouvelle série a été créé');

//            $programRepository->save($program, true);

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to($this->getParameter('mailer_from'))
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('Program/newProgramEmail.html.twig', [
                    'program' => $program,
                    ]));

            $mailer->send($email);

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
                                Request $request,
                                EntityManagerInterface $entityManager,
                                CommentRepository $commentRepository,
    ): Response
    {
        // Récupérer le commentaire de l'utilisateur pour cet épisode
        $comment = $commentRepository->findOneBy(['episode' => $episode, 'author' => $this->getUser()]);


        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $comment->setOwner($this->getUser());
            $comment->setEpisode($episode);
            $comment->setAuthor($user);
            $comment->setCreatedAt(new \DateTime());

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('program_episode_show', [
                'slug' => $program ->getSlug(),
                'number' => $season->getNumber(),
                'slugEpisode' => $episode->getSlug(),
            ]);
        }

    // Initialiser la variable $commentToEdit à null
//        $commentToEdit = null;

    // Vérifier si une requête de edit a été soumise
        if ($request->getMethod() === 'POST' && $commentId = $request->request->get('edit_comment_id')) {
            $commentToEdit = $commentRepository->find($commentId);


    // Ajouter une vérification pour éviter une erreur si $commentToEdit n'est pas défini
        if (($this->getUser() !== $commentToEdit->getOwner()) && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Seul le propriétaire ou l\'administrateur peut modifier le commentaire!');
        }}

        // Vérifier si une requête de suppression a été soumise
        if ($request->getMethod() === 'POST' && $commentId = $request->request->get('delete_comment_id')) {
            // Récupérer le commentaire à supprimer
            $commentToDelete = $commentRepository->find($commentId);

            // Vérifier si l'utilisateur est autorisé à supprimer le commentaire
            if (($this->getUser() !== $commentToDelete->getOwner()) && !$this->isGranted('ROLE_ADMIN')) {
                // Si l'utilisateur n'est pas le propriétaire, throws a 403 Access Denied exception
                throw $this->createAccessDeniedException('Seul le propriétaire peut supprimer son commentaire!');
            }

            // Supprimer le commentaire
            $entityManager->remove($commentToDelete);
            $entityManager->flush();

            // Rediriger vers la page de l'épisode après la suppression du commentaire
            return $this->redirectToRoute('program_episode_show', [
                'slug' => $program->getSlug(),
                'number' => $season->getNumber(),
                'slugEpisode' => $episode->getSlug(),
            ]);
        }

        $commentsSorted =  $commentRepository->findBy(
            ['episode' => $episode],
            ['createdAt' => 'ASC']
        );

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'form' => $form,
            'commentsSorted' => $commentsSorted,
            'commentRepository' => $commentRepository,
        ]);
    }

    #[Route('/edit/{slug}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {

            if (($this->getUser() !== $program->getOwner()) && (!$this->isGranted('ROLE_ADMIN'))) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Seul le propriétaire peut modifier la série!');
        }
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
