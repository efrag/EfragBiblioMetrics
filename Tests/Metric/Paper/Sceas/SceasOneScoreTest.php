<?php

namespace Efrag\Lib\BiblioMetrics\Tests\Metric\Paper\Sceas;

use Efrag\Lib\BiblioMetrics\Metric\Paper\Sceas\SceasOneScore;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphA;

class SceasOneScoreTest extends \PHPUnit_Framework_TestCase
{
    use GraphA;

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotNumericFactorA()
    {
        $metric = new SceasOneScore();
        $metric->setFactorA('test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotNumericFactorB()
    {
        $metric = new SceasOneScore();
        $metric->setFactorB('test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotNumericMaxIterations()
    {
        $metric = new SceasOneScore();
        $metric->setMaxIterations('test');
    }

    /**
     * @expectedException \Exception
     */
    public function testNotInitializedMissingArgument()
    {
        $metric = new SceasOneScore();
        $metric->setPaperCitations([])->getScores();
    }

    /**
     * @expectedException \Exception
     */
    public function testNotMatchingCitationsAndReferences()
    {
        $metric = new SceasOneScore();
        $metric->setPaperCitations(['a' => ['b'], 'b' => []])->setPaperReferences([])->getScores();
    }

    /**
     * DataProvider method to retrieve the cases for testing setting the factors. Factors are float values
     * @return array
     */
    public function factorValueCases()
    {
        $cases = [];

        $cases[] = [1, 1.00000];
        $cases[] = [1.4, 1.40000];
        $cases[] = [1.333333, 1.33333];

        return $cases;
    }

    /**
     * @dataProvider factorValueCases
     */
    public function testSettingFactorA($factor, $expected)
    {
        $metric = new SceasOneScore();
        $factorA = $metric->setFactorA($factor)->getFactorA();

        $this->assertEquals($expected, $factorA, 'Not matching FactorA values', 0.00001);
    }

    /**
     * @dataProvider factorValueCases
     */
    public function testSettingFactorB($factor, $expected)
    {
        $metric = new SceasOneScore();
        $factorB = $metric->setFactorB($factor)->getFactorB();

        $this->assertEquals($expected, $factorB, 'Not matching FactorB values', 0.00001);
    }

    /**
     * DataProvider method to retrieve the cases for testing setting the max iterations for the algorithm. Integer
     * values
     * @return array
     */
    public function maxIterationValueCases()
    {
        $cases = [];

        $cases[] = [1, 1];
        $cases[] = [1.4, 1];
        $cases[] = [1.55, 1];

        return $cases;
    }

    /**
     * @dataProvider maxIterationValueCases
     */
    public function testSettingMaxIterations($iterations, $expected)
    {
        $metric = new SceasOneScore();
        $maxIterations = $metric->setMaxIterations($iterations)->getMaxIterations();

        $this->assertEquals($expected, $maxIterations, 'Not matching Max Iteration values');
    }

    public function testSceasOneScore()
    {
        $expected = [
            1 => 2.7594431268105,
            2 => 0.18393984431332,
            3 => 0.25160757696533,
            4 => 0.25160757696533,
            5 => 0.36787968862663,
            6 => 0,
            7 => 0.69151138345028,
            8 => 0.69151138345028,
            9 => 0,
            10 => 0.62227268105209,
        ];

        $metric = new SceasOneScore();
        $scores = $metric
            ->setPaperCitations($this->graphA['citations'])
            ->setPaperReferences($this->graphA['references'])
            ->getScores();

        $this->assertEquals($scores, $expected);
    }
}
