<?php

namespace GeekTastic\GuessingGame\Contracts;
interface MultiplayerGuessingGame
{
    function getGameStrings(): array;

    function submitGuess(string $playerName, string $submission);
}