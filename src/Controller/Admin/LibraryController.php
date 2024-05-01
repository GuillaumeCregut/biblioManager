<?php

namespace App\Controller\Admin;

use App\Entity\Library;
use App\Entity\Shelf;
use App\Form\LibraryType;
use App\Form\NameType;
use App\Repository\LibraryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/library', name: 'library_')]
class LibraryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(LibraryRepository $lib): Response
    {
        $libraries = $lib->findAll();
        return $this->render('admin/library/index.html.twig', [
            'libraries' => $libraries,
        ]);
    }

    #[Route('/detail/{id}', name: 'detail', methods: ['GET', 'POST'])]
    public function detail(
        Library $library,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(NameType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $shelf = new Shelf();
            $shelf->setName($form->get('name')->getData());
            $shelf->setLibrary($library);
            $em->persist($shelf);
            $em->flush();
            $this->addFlash('success', "L'étagère a bien été ajoutée");
            return $this->redirectToRoute('library_detail', ['id' => $library->getId()]);
        }
        return $this->render('admin/library/detail.html.twig', [
            'library' => $library,
            'form'  => $form
        ]);
    }
    #[Route('/update/{id}', name: 'update', methods: ['GET', 'POST'])]
    public function update(Library $library, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(LibraryType::class, $library);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($library);
            $em->flush();
            $this->addFlash('success', 'La bibliothèque a bien été modifiée');
            return $this->redirectToRoute('library_index');
        }
        return $this->render('admin/library/update.html.twig', [
            'form' => $form,
            'library' => $library,
        ]);
    }
    #[Route('/delete/{id}', name: 'delete', methods: ['POST', 'DELETE'])]
    public function delete(Library $library, EntityManagerInterface $em): Response
    {
        dd($library);
    }
}
