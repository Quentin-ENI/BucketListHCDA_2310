<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/wish', name: 'app_wish_')]
class WishController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SluggerInterface $slugger
    ) {}

    #[Route('/', name: 'list')]
    public function list(): Response
    {
        // FindByCustom() existe pour chaque attribut de l'entité
        return $this->render('wish/list.html.twig', [
            'title' => 'Wish List',
            'wishes' => $this->em->getRepository(Wish::class)->findBy([], ['createdAt' => 'DESC'])
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            $wish->setAuthor($this->getUser()->getUserIdentifier());
            $thumbnailFile = $wishForm->get('thumbnailFile')->getData();
            if ($thumbnailFile !== null) {
                // #TODO On commence sérieusement à se répéter, il serait temps de faire un service
                // pour sortir cette logique d'enregistrement d'un fichier
                $originalFilename = pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $thumbnailFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $thumbnailFile->move($this->getParameter('upload_dir_wishes'), $newFilename);
                    $wish->setThumbnail($newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    dd($e);
                }
            }

            $this->em->persist($wish);
            $this->em->flush();
            $this->addFlash('success', 'Wish ajoute avec succes !!');
            return $this->redirectToRoute('app_wish_detail', ['id' => $wish->getId()]);
        }
        return $this->render('wish/add.html.twig', [
            'title' => 'Wish Add',
            'formWish' => $wishForm
        ]);
    }

    #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET'])]
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

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_USER")]
    public function edit(Wish $wish, Request $request): Response
    {
        if (!$wish) {
            throw $this->createNotFoundException('Ce wish n\'existe pas !!');
        }


        $isAdmin = in_array('ROLE_ADMIN', $this->getUser()->getRoles());
        $isAuthor = $this->getUser()->getUserIdentifier() == $wish->getAuthor();

        if (!($isAdmin and $isAuthor)) {
            $this->denyAccessUnlessGranted("Modification impossible");
        }

        // Valid edit form
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            // File upload
            $thumbnailFile = $wishForm->get('thumbnailFile')->getData();
            // #TODO On commence sérieusement à se répéter, il serait temps de faire un service
            // pour sortir cette logique d'enregistrement d'un fichier
            if ($thumbnailFile !== null) {
                $originalFilename = pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $thumbnailFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    if ($wish->getThumbnail() && \file_exists($this->getParameter('upload_dir_wishes'), $newFilename)) {
                        // Supprimer le fichier s'il existe
                        \unlink($this->getParameter('upload_dir_wishes'), $wish->getThumbnail());
                    }
                    $thumbnailFile->move($this->getParameter('upload_dir_wishes'), $newFilename);
                    $wish->setThumbnail($newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    dd($e);
                }
            }

            $this->em->flush();
            $this->addFlash('success', 'Wish modifié avec succes !!');
            return $this->redirectToRoute('app_wish_detail', ['id' => $wish->getId()]);
        }
        return $this->render('wish/edit.html.twig', [
            'title' => 'Wish Edit',
            'formWish' => $wishForm
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function delete(Wish $wish): Response
    {
        if (!$wish) {
            throw $this->createNotFoundException('Ce wish n\'existe pas !!');
        }

        $isRoleUser = in_array('ROLE_USER', $this->getUser()->getRoles());
        $isAuthor = $wish->getAuthor() == $this->getUser()->getUserIdentifier();

        if ($isRoleUser or $isAuthor) {
            $this->em->remove($wish);
            $this->em->flush();
            $this->addFlash('success', 'Wish a été supprimé avec succes !!');
        } else {
            $this->addFlash('alert', "Le souhait n'a pas été supprimé");
        }

        return $this->redirectToRoute('app_wish_list');
    }
}
