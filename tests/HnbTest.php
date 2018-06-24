<?php

namespace Acme\Test;

use Acme\Hnb;
use Acme\HnbResponse;
use PHPUnit\Framework\TestCase;

class HnbTest extends TestCase
{
    public function testStartHappyPath()
    {
        $hnb = new Hnb();
        $hnb->setCharacters(['a', 'b', 'c'])
            ->setLevel(3)
            ->start();
        
        $this->assertRegExp('/[abc]{3}/', implode('', $hnb->getAnswer()));
        $this->assertCount(
            count(array_unique($hnb->getAnswer())),
            $hnb->getAnswer(),
            'Answer must be a colletion of unqiue characters'
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStartWithInvalidLevel()
    {
        $hnb = new Hnb();
        $hnb->setCharacters(['a'])
            ->setLevel(2)
            ->start();
    }

    public function testAttempt()
    {
        $hnb = new Hnb();

        $this->assertInstanceOf(HnbResponse::class, $hnb->attempt([]));
    }
}
