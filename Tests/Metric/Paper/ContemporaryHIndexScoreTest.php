<?php

namespace Efrag\Lib\BiblioMetrics\Tests\Metric\Paper;

use Efrag\Lib\BiblioMetrics\Metric\Paper\ContemporaryHIndexScore;

class ContemporaryHIndexScoreTest extends \PHPUnit_Framework_TestCase
{
    public function ContemporaryHIndexCases()
    {
        $cases = [];

        $cases[] = [
            4,
            ['a' => ['b'], 'b' => ['c', 'd'], 'c' => [], 'd' => []],
            ['a' => 2016 - 2014 + 1, 'b' => 2016 - 2014 + 1, 'c' => 2016 - 2015 + 1, 'd' => 2016 - 2016 + 1],
            ['a' => 1.3333, 'b' => 2.6667, 'c' => 0, 'd' => 0],
        ];

        return $cases;
    }

    /**
     * @dataProvider ContemporaryHIndexCases
     */
    public function testContemporaryHIndex($gama, $paperCitations, $paperAge, $paperScores)
    {
        $metric = new ContemporaryHIndexScore();
        $scores = $metric
            ->setPaperCitations($paperCitations)
            ->setDecimalPlaces(4)
            ->setGama($gama)
            ->setPaperAge($paperAge)
            ->getScores();

        $this->assertEquals($scores, $paperScores, 'The paper scores do not match');
    }

    /**
     * @expectedException \Exception
     */
    public function testNotInitializedPaperAge()
    {
        $metric = new ContemporaryHIndexScore();
        $metric->setPaperCitations([])->getScores();
    }

    /**
     * @expectedException \Exception
     */
    public function testNotInitializedPaperCitations()
    {
        $metric = new ContemporaryHIndexScore();
        $metric->setPaperAge([])->getScores();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingInvalidDecimalPlaces()
    {
        $metric = new ContemporaryHIndexScore();
        $metric->setDecimalPlaces('test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingInvalidGama()
    {
        $metric = new ContemporaryHIndexScore();
        $metric->setGama('test');
    }
}
