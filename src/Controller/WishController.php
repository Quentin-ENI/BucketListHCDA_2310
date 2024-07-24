<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/{id<\d+>}', name: 'detail')]
//    public function detail(int $id, WishRepository $wishRepository): Response
    public function detail(Wish $wish): Response
    {
//        $wish = $wishRepository->find($id);

        if (!$wish) {
            throw $this->createNotFoundException('Ce wish n\'existe pas !!');
        }
        return $this->render('wish/detail.html.twig', [
            'title' => 'Wish Detail',
            'wish' => $wish
        ]);
    }
}
