<?php

namespace Acme\Test;

use Acme\Alphanumeric;
use Acme\Hnb;
use Acme\HnbSolver;
use Acme\Solvers\BruteForceSolver;
use Acme\Solvers\SlightlyCarefulSolver;
use PHPUnit\Framework\TestCase;

class HnbSolverTest extends TestCase
{
    public function testGetNextAnswer()
    {
        $solver = new DummySolver(new Hnb());

        $this->assertInternalType('array', $solver->getNextAnswer());
    }

    private function getMaxAttempts($numCharacters, $level)
    {
        $result = 1;

        // ex) $numCharacters=5, $level=3
        //     5 * 4 * 3
        while ($level-- > 0) {
            $result *= $numCharacters--;
        }

        return $result;
    }

    /**
     * @dataProvider solvers
     */
    public function testHappyPath(string $solver, array $characters, int $level)
    {
        $hnb = new Hnb();
        $hnb->setCharacters($characters)
            ->setLevel($level)
            ->start();

        $solver = new $solver($hnb);

        $maxAttempts = $this->getMaxAttempts(count($hnb->getCharacters()), $hnb->getLevel());

        echo PHP_EOL;
        echo 'Answer: ', implode(' ', $hnb->getAnswer()), PHP_EOL;
        echo 'MaxAttempts: ', $maxAttempts, PHP_EOL;

        for ($i = 1; $i <= $maxAttempts; $i++) {
            $answer = $solver->getNextAnswer();
            $response = $hnb->attempt($answer);

            echo 'Hit: ', $response->getHit(),
                ', Blow: ', $response->getBlow(),
                ', Answer: ', implode(' ', $answer), PHP_EOL;

            if ($hnb->isSolved($response)) {
                echo sprintf('Solved at %d!', $i), PHP_EOL;
                break;
            }

            $solver->addHint($response);
        }

        $this->assertTrue($hnb->isSolved($response), sprintf('Could not solve the game within %d attempts...', $maxAttempts));
    }

    public function solvers()
    {
        return [
            [BruteForceSolver::class, ['a', 'b', 'c', 'd', 'e'], 1],
            [SlightlyCarefulSolver::class, ['a', 'b', 'c', 'd', 'e'], 3],
            [SlightlyCarefulSolver::class, Alphanumeric::asArray(), 2],
        ];
    }
}

class DummySolver extends HnbSolver
{
    public function getNextAnswer(): array
    {
        return [];
    }
}
