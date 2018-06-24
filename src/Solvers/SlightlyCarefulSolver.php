<?php

namespace Acme\Solvers;

use Acme\Hnb;
use Acme\HnbSolver;
use Acme\Permutation;

class SlightlyCarefulSolver extends HnbSolver
{
    use Permutation;

    private $possibles = [];

    private $simulator;

    protected function prepare(): self
    {
        $this->permute($this->getHnb()->getCharacters());
        $this->possibles = $this->getPermutations();

        return $this;
    }

    private function getNextAnswerCandidate(): array
    {
        return array_pop($this->possibles);
    }

    private function guess(): bool
    {
        $this->simulator->setAnswer($this->getNextAnswerCandidate());

        // This new answer must return the same response with the previous attempts.
        foreach ($this->getHints() as $previousResult) {
            $response = $this->simulator->attempt($previousResult->getAnswer());

            if (!$response->isEqual($previousResult)) {
                return false;
            }
        }

        return true;
    }

    public function getNextAnswer(): array
    {
        if (empty($this->getHints())) {
            return $this->getNextAnswerCandidate();
        }

        $this->simulator = new Hnb();
        $this->simulator->setCharacters($this->getHnb()->getCharacters())
            ->setLevel($this->getHnb()->getLevel());

        while (!$this->guess());

        return $this->simulator->getAnswer();
    }
}
