<?php

namespace Tests\Unit;

use GeekTastic\GuessingGame\Actions\PlayerScoreAction;
use GeekTastic\GuessingGame\Services\GuessingGameService;
use GeekTastic\GuessingGame\Services\VocabularyCheckerImpl;
use PHPUnit\Framework\TestCase;

class MultiplayerGuessingGameTest extends TestCase
{
    private GuessingGameService $guessingGameService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guessingGameService = GuessingGameService::withWords(['poker', 'cover', 'pesto'])
            ->withVocabularyChecker(new VocabularyCheckerImpl())
            ->withScoreManager(new PlayerScoreAction());
    }

    public function testGameInitialization()
    {
        $this->assertCount(3, $this->guessingGameService->getGameStrings());
    }

    public function testValidSubmission()
    {
        $score = $this->guessingGameService->submitGuess('Player1', 'power');
        $this->assertGreaterThan(0, $score);
    }

    public function testInvalidSubmission()
    {
        $score = $this->guessingGameService->submitGuess('Player1', 'bunch');
        $this->assertEquals(0, $score);
    }

    public function testExactMatch()
    {
        $score = $this->guessingGameService->submitGuess('Player1', 'poker');
        $this->assertEquals(10, $score);
    }
}