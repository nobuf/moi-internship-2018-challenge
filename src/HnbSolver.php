<?php

namespace Acme;

abstract class HnbSolver
{
    private $hnb;

    private $hints = [];

    public function __construct(Hnb $hnb)
    {
        $this->hnb = $hnb;

        $this->prepare();
    }

    protected function prepare()
    {
        // overwrite this otherwise do nothing
    }

    protected function getHnb(): Hnb
    {
        return $this->hnb;
    }

    protected function getHints(): array
    {
        return $this->hints;
    }

    public function addHint(HnbResponse $response): self
    {
        $this->hints[] = $response;

        return $this;
    }

    abstract public function getNextAnswer(): array;
}
