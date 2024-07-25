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

#[Route('/wish', name: 'app_wish_')]
class WishController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(WishRepository $wishRepository): Response
    {
        // FindByCustom() existe pour chaque attribut de l'entité

        return $this->render('wish/list.html.twig', [
            'title' => 'Wish List',
            'wishes' => $wishRepository->findBy([], ['createdAt' => 'DESC'])
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            $em->persist($wish);
            $em->flush();
            $this->addFlash('success', 'Wish ajoute avec succes !!');
            return $this->redirectToRoute('app_wish_detail', ['id' => $wish->getId()]);
        }
        return $this->render('wish/add.html.twig', [
            'title' => 'Wish Add',
            'formWish' => $wishForm
        ]);
    }

    #[Route('/{id<\d+>}', name: 'detail')]
//    public function detail(int $id, WishRepository $wishRepository): Response
    public function detail(Wish $wish): Response
    {
        if (!$wish) {
            throw $this->createNotFoundException('Ce wish n\'existe pas !!');
        }
        return $this->render('wish/detail.html.twig', [
            'title' => 'Wish Detail',
            'wish' => $wish
        ]);
    }
}
