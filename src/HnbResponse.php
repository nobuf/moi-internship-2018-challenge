<?php

namespace Acme;

class HnbResponse
{
    private $answer;

    private $hit;

    private $blow;

    public function __construct(array $answer, int $hit, int $blow)
    {
        $this->answer = $answer;
        $this->hit = $hit;
        $this->blow = $blow;
    }

    public function getAnswer(): array
    {
        return $this->answer;
    }

    public function getHit(): int
    {
        return $this->hit;
    }

    public function getBlow(): int
    {
        return $this->blow;
    }

    public function isEqual(HnbResponse $anotherResponse): bool
    {
        return $this->getHit() === $anotherResponse->getHit()
            && $this->getBlow() === $anotherResponse->getBlow();
    }
}
