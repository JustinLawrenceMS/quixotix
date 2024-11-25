<?php

namespace Quixotify;

use PDO;
use Exception;

class Controller
{
    private PDO $dbConnection;

    public function __construct()
    {
        $dbFilePath = __DIR__ . '/database.db';
        $this->dbConnection = new PDO('sqlite:' . $dbFilePath);
    }

    private function validateInput(int $quantity, string $unit): void
    {
        $validUnits = ['characters', 'words', 'sentences'];
        if (!in_array($unit, $validUnits, true)) {
            $err = new Exception('Invalid type provided. Accepted types: characters, words, sentences.');
            file_put_contents("php://stderr", $err);
        }

        if ($quantity <= 0) {
            $err = new Exception('Quantity must be a positive integer.');
            file_put_contents("php://stderr", $err);
        }
    }

    private function fetchRandomText(): array
    {
        $query = 'SELECT * FROM don_quixote_texts ORDER BY RANDOM() LIMIT 1';
        $statement = $this->dbConnection->query($query);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function generateText($unit, $quantity): string
    {
        $this->validateInput($quantity, $unit);
        $sourceText = $this->fetchRandomText();

        return match ($unit) {
            'characters' => $this->generateCharacterText($sourceText, $quantity),
            'words' => $this->generateWordText($sourceText, $quantity),
            'sentences' => $this->generateSentenceText($sourceText, $quantity),
            default => file_put_contents("php://stderr", (new Exception('Invalid unit type.'))),
        };
    }

    private function generateCharacterText($sourceText, $characterCount): string
    {
        $sourceId = $sourceText['id'];
        $requiredRows = ceil($characterCount / 75);
        $query = "SELECT TRIM(text) FROM don_quixote_texts WHERE id >= :id LIMIT :limit";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindValue(':id', $sourceId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $requiredRows, PDO::PARAM_INT);
        $stmt->execute();

        $textData = implode(' ', array_map('trim', $stmt->fetchAll(PDO::FETCH_COLUMN)));
        $finalText = $this->truncateTextToCharacters($textData, $characterCount);

        while (mb_strlen($finalText, 'UTF-8') < $characterCount) {
            $finalText = $this->appendMoreCharacters($finalText, $characterCount);
        }

        return $this->addEllipsis($finalText, $characterCount);
    }

    private function generateWordText($sourceText, $wordCount): string
    {
        $buffer = 10; // To handle edge cases with missing data
        $requiredRows = ceil($wordCount / $sourceText['word_count']) + $buffer;
        $query = "SELECT TRIM(text) FROM don_quixote_texts WHERE id >= :id LIMIT :limit";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindValue(':id', $sourceText['id'], PDO::PARAM_INT);
        $stmt->bindValue(':limit', $requiredRows, PDO::PARAM_INT);
        $stmt->execute();

        $texts = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $wordArray = explode(' ', implode(' ', $texts));

        // If there are not enough words, fetch additional rows
        while (count($wordArray) < $wordCount) {
            $wordArray = $this->appendMoreWords($wordArray);
        }

        return implode(' ', array_slice($wordArray, 0, $wordCount));
    }

    private function generateSentenceText($sourceText, $sentenceCount): string
    {
        $query = "SELECT text FROM don_quixote_texts WHERE id >= :id LIMIT :limit";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindValue(':id', $sourceText['id'], PDO::PARAM_INT);
        $stmt->bindValue(':limit', $sentenceCount, PDO::PARAM_INT);
        $stmt->execute();

        $sentences = array_filter($stmt->fetchAll(PDO::FETCH_COLUMN));

        // If there are not enough sentences, fetch additional rows
        while (count($sentences) < $sentenceCount) {
            $sentences = $this->appendMoreSentences($sentences);
        }

        return implode(' ', array_slice($sentences, 0, $sentenceCount));
    }

    private function truncateTextToCharacters(string $text, int $characterLimit): string
    {
        return mb_substr($text, 0, $characterLimit, 'UTF-8');
    }

    private function appendMoreCharacters(string $text, int $requiredLength): string
    {
        $additionalText = $this->generateText('characters', $requiredLength);
        return $this->truncateTextToCharacters($text . ' ' . $additionalText, $requiredLength);
    }

    private function appendMoreWords(array $currentWords): array
    {
        $query = "SELECT TRIM(text) FROM don_quixote_texts ORDER BY RANDOM() LIMIT 1";
        $stmt = $this->dbConnection->query($query);
        $newWords = explode(' ', implode(' ', $stmt->fetchAll(PDO::FETCH_COLUMN)));

        return array_merge($currentWords, $newWords);
    }

    private function appendMoreSentences(array $currentSentences): array
    {
        $query = "SELECT text FROM don_quixote_texts ORDER BY RANDOM() LIMIT 1";
        $stmt = $this->dbConnection->query($query);
        $newSentences = array_filter($stmt->fetchAll(PDO::FETCH_COLUMN));

        return array_merge($currentSentences, $newSentences);
    }

    private function addEllipsis(string $text, int $limit): string
    {
        if ($limit > 3) {
            return mb_substr($text, 0, -3, 'UTF-8') . '...';
        }
        return $text;
    }
}