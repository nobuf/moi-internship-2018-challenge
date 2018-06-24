<?php

namespace Acme;

/**
 * Hit & Blow Game
 *
 */
class Hnb
{
    private $characters;

    private $level;

    private $answer;

    public function setCharacters(array $characters): self
    {
        $this->characters = $characters;

        return $this;
    }

    public function getCharacters(): array
    {
        return $this->characters;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setAnswer(array $answer): self
    {
        $this->answer = $answer;
        
        return $this;
    }

    public function getAnswer(): array
    {
        return $this->answer;
    }

    private function validate(): void
    {
        if (count($this->getCharacters()) < $this->getLevel()) {
            throw new \InvalidArgumentException(
                sprintf(
                    '# of characters (%d) must be equal or greater than level (%d) to generate answer',
                    count($this->getCharacters()),
                    $this->getLevel()
                )
            );
        }
    }

    private function generateAnswer(): array
    {
        $indexes = (array)array_rand($this->getCharacters(), $this->getLevel());

        return array_map(function ($index) {
            return $this->getCharacters()[$index];
        }, $indexes);
    }

    public function start(): self
    {
        $this->validate();

        $this->setAnswer($this->generateAnswer());

        return $this;
    }

    public function isSolved(HnbResponse $response): bool
    {
        return $response->getHit() === $this->getLevel();
    }

    private function isHit(int $index, string $value): bool
    {
        return $this->getAnswer()[$index] === $value;
    }

    private function isBlow(string $value): bool
    {
        return in_array($value, $this->getAnswer());
    }

    public function attempt(array $answer): HnbResponse
    {
        $hit = 0;
        $blow = 0;

        foreach ($answer as $index => $value) {
            if ($this->isHit($index, $value)) {
                $hit++;
            } elseif ($this->isBlow($value)) {
                $blow++;
            }
        }

        return new HnbResponse($answer, $hit, $blow);
    }
}
