<?php

namespace App\Controller;

use App\Form\SearchEventType;
use App\Model\SearchEvent;
use App\Service\EventApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    #[Route('/events', name: 'app_events')]
    public function list(
        Request $request,
        SearchEvent $searchEvent,
        EventApiService $eventApiService
    ): Response
    {
        $searchEvent = new SearchEvent();
        $searchEventForm = $this->createForm(SearchEventType::class, $searchEvent);
        $searchEventForm->handleRequest($request);

        $events = [];

        if ($searchEventForm->isSubmitted() && $searchEventForm->isValid()) {
            $events = $eventApiService->search($searchEvent);
        }

        dump($events);

        return $this->render('event/index.html.twig', [
            'searchEventForm' => $searchEventForm,
            'events' => $events
        ]);
    }
}
