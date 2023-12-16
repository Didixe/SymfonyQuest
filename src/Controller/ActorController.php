<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Form\ActorType;
use App\Repository\ActorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/actor')]
class ActorController extends AbstractController
{
    #[Route('/', name: 'app_actor')]
    public function index(ActorRepository $actorsRepository): Response
    {
        $actors= $actorsRepository->findAll();
        return $this->render('actor/index.html.twig', [
//            'controller_name' => 'ActorController',
            'actors' => $actors,
        ]);
    }

    #[Route('/new', name:'app_actor_new', methods: ['GET', 'POST'])]
    public function new(Request$request, EntityManagerInterface $entityManager): Response
    {
        $actor = new Actor();
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($actor);
            $entityManager->flush();

            $this->addFlash('success', 'Le nouvel acteur a été créé');

            return $this->redirectToRoute('app_actor', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('actor/new.html.twig', [
            'actor' => $actor,
            'form' => $form,
        ]);
    }


    #[Route('/show/{id}', name: 'app_actor_show', methods: ['GET'])]
    public function show(Actor $actor): Response
    {
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }

}
