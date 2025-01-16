<?php

namespace GeekTastic\GuessingGame\Services;

use GeekTastic\GuessingGame\Actions\PlayerScoreAction;
use GeekTastic\GuessingGame\Contracts\MultiplayerGuessingGame;
use GeekTastic\GuessingGame\Contracts\VocabularyChecker;


class GuessingGameService implements MultiplayerGuessingGame
{
    private array $words = [];
    private array $maskedWords = [];
    private VocabularyChecker $vocabularyChecker;

    private PlayerScoreAction $playerScoreAction;

    private function __construct(array $gameWords)
    {
        foreach ($gameWords as $word) {
            $this->words[] = $word;
            $randomIndex = rand(0, strlen($word) - 1);
            $maskedWord = str_repeat('*', strlen($word));
            $maskedWord[$randomIndex] = $word[$randomIndex];
            $this->maskedWords[] = $maskedWord;
        }
    }

    /**
     * @param array $gameWords
     * @return GuessingGameService
     */
    public static function withWords(array $gameWords): GuessingGameService
    {
        return new self($gameWords);
    }

    /**
     * @param VocabularyChecker $vocabularyChecker
     * @return $this
     */
    public function withVocabularyChecker(VocabularyChecker $vocabularyChecker): GuessingGameService
    {
        $this->vocabularyChecker = $vocabularyChecker;
        return $this;
    }

    /**
     * @param PlayerScoreAction $playerScoreAction
     * @return $this
     */
    public function withScoreManager(PlayerScoreAction $playerScoreAction): GuessingGameService
    {
        $this->playerScoreAction = $playerScoreAction;
        return $this;
    }

    /**
     * @return array
     */
    public function getGameStrings(): array
    {
        return $this->maskedWords;
    }

    /**
     * @param string $playerName
     * @param string $submission
     * @return int
     */
    public function submitGuess(string $playerName, string $submission): int
    {
        $submission = strtolower($submission);

        if (!$this->vocabularyChecker->exists($submission)) {
            return 0;
        }

        $totalRevealed = 0;
        $isExactMatch = false;
        foreach ($this->words as $index => $originalWord) {
            if ($this->maskedWords[$index] === $originalWord) {
                continue;
            }

            if ($this->matchesRevealed($this->maskedWords[$index], $submission)) {
                if ($submission === $originalWord) {
                    $isExactMatch = true;
                    $this->maskedWords[$index] = $originalWord;
                    break;
                } else {
                    $totalRevealed += $this->revealCharacters($index, $submission);
                }
            }
        }

        if ($isExactMatch) {
            $this->playerScoreAction->updateScore($playerName, 10);
            return 10;
        }
        $this->playerScoreAction->updateScore($playerName, $totalRevealed);
        return $totalRevealed;
    }

    //TODO; this can be refactor to separate Service class
    private function matchesRevealed(string $revealedWord, string $submission): bool
    {
        for ($i = 0; $i < strlen($revealedWord); $i++) {
            if ($revealedWord[$i] !== '*' && $revealedWord[$i] !== $submission[$i]) {
                return false;
            }
        }
        return true;
    }

    //TODO; this can be refactor to separate Service class
    private function revealCharacters(int $index, string $submission): int
    {
        $revealCount = 0;
        for ($i = 0; $i < strlen($this->words[$index]); $i++) {
            if ($this->maskedWords[$index][$i] === '*' && $this->words[$index][$i] === $submission[$i]) {
                $this->maskedWords[$index][$i] = $this->words[$index][$i];
                $revealCount++;
            }
        }
        return $revealCount;
    }
}