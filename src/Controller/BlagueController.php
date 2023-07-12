<?php

namespace App\Controller;

use App\Entity\Blague;
use App\Form\BlagueType;
use App\Repository\BlagueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blague')]
class BlagueController extends AbstractController
{
    #[Route('/', name: 'app_blague_index', methods: ['GET'])]
    public function index(BlagueRepository $blagueRepository): Response
    {
        return $this->render('blague/index.html.twig', [
            'blagues' => $blagueRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_blague_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $blague = new Blague();
        $form = $this->createForm(BlagueType::class, $blague);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($blague);
            $entityManager->flush();

            return $this->redirectToRoute('app_blague_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blague/new.html.twig', [
            'blague' => $blague,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blague_show', methods: ['GET'])]
    public function show(Blague $blague): Response
    {
        return $this->render('blague/show.html.twig', [
            'blague' => $blague,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_blague_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Blague $blague, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlagueType::class, $blague);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_blague_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blague/edit.html.twig', [
            'blague' => $blague,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blague_delete', methods: ['POST'])]
    public function delete(Request $request, Blague $blague, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blague->getId(), $request->request->get('_token'))) {
            $entityManager->remove($blague);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blague_index', [], Response::HTTP_SEE_OTHER);
    }
}
