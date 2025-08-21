<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WishController extends AbstractController
{
    #[Route('/wish', name: 'wishAccueil')]
    public function index(): Response
    {
        return $this->render('/base.html.twig', [
            'controller_name' => 'WishController',
        ]);
    }

    #[Route('wish/wishlist', name: 'wishList')]
    public function wishList(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findAll();
        return $this->render('/wishlist/wishList.html.twig', [
            'wishes' => $wishes,
        ]);
    }
    #[Route('wish/detail/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function wishDetail(Wish $wish): Response
    {

        if (!$wish) {
            throw $this->createNotFoundException('Pas de voeux');
        }
        return $this->render('wish/detail.html.twig', [
            'wish' => $wish,
        ]);
    }

    #[Route('wish/wishadd', name: 'wishAdd')]
    public function wishAdd(Request $request, EntityManagerInterface $em): Response
    {
        $wish = new Wish();
        $form = $this->createForm(WishType::class, $wish);
//    handleRequest permet d'ajouter les données récupérées dans le form dnas le $wish
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($wish);
            $em->flush();
            $this->addFlash('success','Voeux ajouté !');
            return $this->redirectToRoute('detail', ['id' => $wish->getId()]);
        }
        return $this->render('/wishlist/addwish.html.twig', [
            'wish_form' => $form->createView()
        ]);
    }
}
