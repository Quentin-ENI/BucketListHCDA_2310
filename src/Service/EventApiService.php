<?php

namespace App\Service;

use App\Model\SearchEvent;

class EventApiService
{
    public function search(SearchEvent $searchEvent) {
        $url = "https://public.opendatasoft.com/api/records/1.0/search/?dataset=evenements-publics-openagenda"
            ."&refine.location.city=". strtolower($searchEvent->getCity())
            ."&refine.firstdate_begin=".$searchEvent->getDateEvent()->format('Y-m-d');
        $content = file_get_contents($url);
        if ($content === false) {
            return [];
        } else {
            return json_decode($content, true);
        }
    }
}