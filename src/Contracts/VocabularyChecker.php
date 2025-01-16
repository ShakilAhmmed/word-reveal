<?php

namespace GeekTastic\GuessingGame\Contracts;

interface VocabularyChecker
{
    public function exists(string $word): bool;
}