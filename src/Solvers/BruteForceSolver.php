<?php

namespace Acme\Solvers;

use Acme\HnbSolver;
use Acme\Permutation;

class BruteForceSolver extends HnbSolver
{
    use Permutation;

    private $possibles = [];

    protected function prepare(): self
    {
        $this->permute($this->getHnb()->getCharacters());
        $this->possibles = $this->getPermutations();

        return $this;
    }

    public function getNextAnswer(): array
    {
        return array_pop($this->possibles);
    }
}
