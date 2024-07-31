<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class SearchEvent
{
    #[Assert\NotBlank]
    private \DateTimeImmutable $dateEvent;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $city;

    public function getDateEvent(): \DateTimeImmutable
    {
        return $this->dateEvent;
    }

    public function setDateEvent(\DateTimeImmutable $dateEvent): void
    {
        $this->dateEvent = $dateEvent;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }
}