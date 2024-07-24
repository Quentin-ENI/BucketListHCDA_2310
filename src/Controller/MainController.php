<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'title' => 'Home',
        ]);
    }

    #[Route('/about-us', name: 'app_about_us')]
    public function about(): Response
    {
        // TP 2 Json data
        $jsonData = file_get_contents('../data/team.json');
        $data = json_decode($jsonData, true);
        dump($data);
        return $this->render('main/about.html.twig', [
            'title' => 'About Us',
            'team' => $data
        ]);
    }
}
