<?php

namespace App\Controller\Admin;

use App\Entity\Shelf;
use App\Form\ShelfType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/shelf', name: 'shelf_')]
class ShelfController extends AbstractController
{
    #[Route('/detail/{id}', name: 'detail')]
    public function detail(Shelf $shelf): Response
    {
        return $this->render('admin/shelf/detail.html.twig', [
            'shelf' => $shelf,
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function update(
        Shelf $shelf,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $form = $this->createForm(ShelfType::class, $shelf);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($shelf);
            $em->flush();
            $this->addFlash('success', 'Etagère modifiée');
            return $this->redirectToRoute('shelf_detail', ['id' => $shelf->getId()]);
        }

        return $this->render('admin/shelf/update.html.twig', [
            'shelf' => $shelf,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Shelf $shelf, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $shelf->getId(), $request->getPayload()->get('_token'))) {
            $em->remove($shelf);
            $em->flush();
            $this->addFlash('success', 'Etagère supprimée');
        }
        return $this->redirectToRoute('shelf_index');
    }
}
