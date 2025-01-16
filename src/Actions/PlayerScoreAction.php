<?php

namespace GeekTastic\GuessingGame\Actions;

class PlayerScoreAction
{
    private array $playerScores = [];

    /**
     * @param string $playerName
     * @param int $score
     * @return void
     */
    public function updateScore(string $playerName, int $score): void
    {
        if (!isset($this->playerScores[$playerName])) {
            $this->playerScores[$playerName] = 0;
        }
        $this->playerScores[$playerName] += $score;
    }

    /**
     * @param string $playerName
     * @return int
     */
    public function getScore(string $playerName): int
    {
        return $this->playerScores[$playerName] ?? 0;
    }

    /**
     * @return array
     */
    public function getAllScores(): array
    {
        return $this->playerScores;
    }
}