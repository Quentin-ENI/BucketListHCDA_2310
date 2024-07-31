<?php

namespace App\Service;

class CensuratorService
{
    private array $forbiddenWords = [
        "putain",
        "merde",
        "merdes",
        "chier",
        "mort",
        "mourir",
        "creve"
    ];

    public function purify(string $sentence) : string
    {
        // J'isole tous les mots de la phrase
        // et les stocke dans un tableau
        // Regex : Isole des chaînes de caractères qui ne contiennent pas
        // de caractères spéciaux
        $sentenceWords = preg_split(
            '/(\W+)/u',
            $sentence,
            -1,
            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
        );

        foreach ($sentenceWords as &$word) {
            if (in_array($word, $this->forbiddenWords)) {
                $wordLength = strlen($word);
                $word = str_repeat('*', $wordLength);
            }
        }

        return implode('', $sentenceWords);
    }
}