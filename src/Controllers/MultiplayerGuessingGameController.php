<?php

namespace GeekTastic\GuessingGame\Controllers;

use GeekTastic\GuessingGame\Actions\PlayerScoreAction;
use GeekTastic\GuessingGame\Services\GuessingGameService;
use GeekTastic\GuessingGame\Services\VocabularyCheckerImpl;

class MultiplayerGuessingGameController
{
    public function playGame(): void
    {
        $game = GuessingGameService::withWords(['poker', 'cover', 'pesto'])
            ->withVocabularyChecker(new VocabularyCheckerImpl())
            ->withScoreManager(new PlayerScoreAction());

        print_r($game->getGameStrings());
        echo PHP_EOL;
        $game->submitGuess('Player1', 'power');
    }
}