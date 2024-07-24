<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wish', name: 'app_wish_')]
class WishController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(): Response
    {
        return $this->render('wish/list.html.twig', [
            'title' => 'Wish List',
        ]);
    }

    #[Route('/detail', name: 'detail')]
    public function detail(): Response
    {
        return $this->render('wish/detail.html.twig', [
            'title' => 'Wish Detail',
        ]);
    }
}
