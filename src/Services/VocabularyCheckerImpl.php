<?php

namespace GeekTastic\GuessingGame\Services;

use Exception;
use GeekTastic\GuessingGame\Contracts\VocabularyChecker;

class VocabularyCheckerImpl implements VocabularyChecker
{
    private array $validWords = [];

    public function __construct()
    {
        try {
            //FIXME; We can Consider using dependency injection for the file path instead of hardcoding
            //FIXME; This would make the class more flexible and easier to test
            $handle = fopen(__DIR__ . '/../../wordlist.txt', 'r', false);
            if ($handle !== false) {
                //FIXME; We can  Consider using file_get_contents and explode for better performance
                //FIXME; with large files since it reduces I/O operations
                while (($line = fgets($handle)) !== false) {
                    $this->validWords[] = trim($line);
                }
                fclose($handle);
            } else {
                //FIXME; Consider creating a custom exception class for specific errors
                throw new Exception("Failed to open wordlist.txt");
            }
        } catch (Exception $e) {
            //FIXME; Catching and echoing exceptions is not ideal for a service class
            //FIXME; We can Consider logger service here
            echo $e->getMessage();
        }
    }

    public function exists(string $word): bool
    {
        //FIXME; We can consider here array_key_exists() for better performance
        //FIXME; because in_array is linear operation and time complexity is O(n) where array_key_exists() is O(1)
        //FIXME; to achieve O(1) we can consider $validWords as key value pairs
        return in_array($word, $this->validWords);
    }
}
