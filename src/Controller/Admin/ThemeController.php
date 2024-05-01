<?php

namespace App\Controller\Admin;

use App\Entity\Theme;
use App\Form\ThemeType;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/theme', name: 'theme_')]
class ThemeController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ThemeRepository $themeRepository): Response
    {
        return $this->render('admin/theme/index.html.twig', [
            'themes' => $themeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($theme);
            $entityManager->flush();

            return $this->redirectToRoute('theme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/theme/new.html.twig', [
            'theme' => $theme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Theme $theme): Response
    {
        return $this->render('admin/theme/show.html.twig', [
            'theme' => $theme,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Theme $theme, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('theme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/theme/edit.html.twig', [
            'theme' => $theme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Theme $theme, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $theme->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($theme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('theme_index', [], Response::HTTP_SEE_OTHER);
    }
}
