<?php

namespace App\Controller;

use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Form\CategoryType;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;

#[Route('/category', name: 'category_')]
Class CategoryController extends AbstractController{

    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render(
            'category/index.html.twig',
            ['categories' => $categories]
        );
    }

    #[Route('/show/{categoryName}', name: 'show')]
    public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository):Response
    {
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category with categoryName : '.$categoryName.' found in category\'s table.'
            );
        }

        $programs = $programRepository->findBy(
            ['category' => $category],
            ['id' => 'DESC'],
            3
        );

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'programs' => $programs,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $category = new Category();

        // Create the form, linked with $category
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form,
        ]);
    }
}