<?php

namespace Efrag\Lib\BiblioMetrics\Tests;

use Efrag\Lib\BiblioMetrics\Ranking\Ordinal;

class OrdinalRankingTest extends \PHPUnit_Framework_TestCase
{
    public function OrdinalRankingCases()
    {
        $cases = [];

        $cases[] = [
            ['a' => 150, 'b' => 150, 'c' => 130, 'd' => 130, 'e' => 130, 'f' => 120],
            ['a' => 1.5, 'b' => 1.5, 'c' => 4, 'd' => 4, 'e' => 4, 'f' => 6]
        ];

        $cases[] = [
            ['a' => 150, 'b' => 150, 'c' => 130, 'd' => 130, 'e' => 120, 'f' => 120],
            ['a' => 1.5, 'b' => 1.5, 'c' => 3.5, 'd' => 3.5, 'e' => 5.5, 'f' => 5.5]
        ];

        return $cases;
    }

    /**
     * @dataProvider OrdinalRankingCases
     *
     * @param $entityValue
     * @param $entityRanking
     */
    public function testOrdinal($entityValue, $entityRanking)
    {
        $ordinal = new Ordinal();
        $ranks = $ordinal->setEntityValue($entityValue)->getRanking();

        $this->assertEquals($entityRanking, $ranks, 'The rankings do not match');
    }
}
