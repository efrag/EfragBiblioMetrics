<?php

namespace Efrag\Lib\BiblioMetrics\Tests\Metric\Paper;

use Efrag\Lib\BiblioMetrics\Metric\Paper\Citations;

class CitationsTest extends \PHPUnit_Framework_TestCase
{
    public function CitationsCases()
    {
        $cases = [];

        $cases[] = [
            ['a' => ['b'], 'b' => ['c', 'd'], 'c' => [], 'd' => []],
            ['a' => 1, 'b' => 2, 'c' => 0, 'd' => 0],
        ];

        return $cases;
    }

    /**
     * @dataProvider CitationsCases
     */
    public function testCitations($paperCitations, $paperScores)
    {
        $metric = new Citations();
        $scores = $metric->setPaperCitations($paperCitations)->getScores();

        $this->assertEquals($scores, $paperScores, 'The paper scores do not match');
    }

    /**
     * @expectedException \Exception
     */
    public function testUninitializedCitations()
    {
        $metric = new Citations();
        $metric->getScores();
    }
}
