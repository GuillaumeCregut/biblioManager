<?php

namespace App\Controller\Admin;

use App\Entity\Editor;
use App\Form\EditorType;
use App\Repository\EditorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/editor', name: 'editor_')]
class EditorController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(EditorRepository $editorRepository): Response
    {
        return $this->render('admin/editor/index.html.twig', [
            'editors' => $editorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $editor = new Editor();
        $form = $this->createForm(EditorType::class, $editor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($editor);
            $entityManager->flush();

            return $this->redirectToRoute('editor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/editor/new.html.twig', [
            'editor' => $editor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Editor $editor): Response
    {
        return $this->render('admin/editor/show.html.twig', [
            'editor' => $editor,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Editor $editor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EditorType::class, $editor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('editor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/editor/edit.html.twig', [
            'editor' => $editor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Editor $editor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $editor->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($editor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('editor_index', [], Response::HTTP_SEE_OTHER);
    }
}
