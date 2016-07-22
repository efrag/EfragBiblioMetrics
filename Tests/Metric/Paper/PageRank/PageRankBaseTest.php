<?php
/**
 * Copyright (C) 2016 Eleni Fragkiadaki
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Efrag\Lib\BiblioMetrics\Tests\Metric\Paper\PageRank;

use Efrag\Lib\BiblioMetrics\Metric\Paper\PageRank\PageRankBase;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphA;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphB;

class PageRankBaseTest extends \PHPUnit_Framework_TestCase
{
    use GraphA;
    use GraphB;

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
        $metric = new PageRankBase();
        $maxIterations = $metric->setMaxIterations($iterations)->getMaxIterations();

        $this->assertEquals($expected, $maxIterations, 'Not matching Max Iteration values');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotNumericMaxIterations()
    {
        $metric = new PageRankBase();
        $metric->setMaxIterations('test');
    }

    /**
     * DataProvider method to retrieve the cases for testing setting the factor. The factor is a float value
     *
     * @return array
     */
    public function factorValueCases()
    {
        $cases = [];

        $cases[] = [0, 0.00000];
        $cases[] = [0.75, 0.75000];
        $cases[] = [0.95, 0.95000];

        return $cases;
    }

    /**
     * @dataProvider factorValueCases
     */
    public function testSettingFactor($factor, $expected)
    {
        $metric = new PageRankBase();
        $factor = $metric->setFactor($factor)->getFactor();

        $this->assertEquals($expected, $factor, 'Not matching Factor values', 0.00001);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotNumericFactor()
    {
        $metric = new PageRankBase();
        $metric->setFactor('test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFactorGreaterThanOne()
    {
        $metric = new PageRankBase();
        $metric->setFactor(1.5);
    }

    /**
     * DataProvider method to retrieve the cases for testing setting the factor. The factor is a float value
     *
     * @return array
     */
    public function epsilonValueCases()
    {
        $cases = [];

        $cases[] = [0, 0.00000];
        $cases[] = [0.00002, 0.00002];
        $cases[] = [0.0005, 0.0005];

        return $cases;
    }

    /**
     * @dataProvider factorValueCases
     */
    public function testSettingEpsilon($epsilon, $expected)
    {
        $metric = new PageRankBase();
        $epsilon = $metric->setEpsilon($epsilon)->getEpsilon();

        $this->assertEquals($expected, $epsilon, 'Not matching epsilon values', 0.000001);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotNumericEpsilon()
    {
        $metric = new PageRankBase();
        $metric->setEpsilon('test');
    }

    public function testPageRankBaseForGraphA()
    {
        $expected = [
            1 => 3.6609898896725,
            2 => 0.21375,
            3 => 0.2679375,
            4 => 0.2679375,
            5 => 0.2775,
            6 => 0.15,
            7 => 1.7059207031108,
            8 => 1.7059207031108,
            9 => 0.15,
            10 => 1.6000316971203,
        ];

        $metric = new PageRankBase();
        $scores = $metric
            ->setPaperCitations($this->graphA['citations'])
            ->setPaperReferences($this->graphA['references'])
            ->getScores();

        $this->assertEquals($expected, $scores, 'The PageRankBase scores do not match');
    }

    public function testPageRankBaseForGraphB()
    {
        $expected = [
            1 => 2.1150459375,
            2 => 1.43169375,
            3 => 0.2775,
            4 => 0.602625,
            5 => 1.507875,
            6 => 0.15,
            7 => 0.5325,
            8 => 0.15,
            9 => 1.2975,
            10 => 0.15,
            11 => 0.15,
            12 => 0.15,
            13 => 0.15,
            14 => 0.15,
            15 => 0.15,
            16 => 0.15,
            17 => 0.15,
            18 => 0.15,
            19 => 0.15,
            20 => 0.15,
            21 => 0.15,
            22 => 0.15,
        ];

        $metric = new PageRankBase();
        $scores = $metric
            ->setPaperCitations($this->graphB['citations'])
            ->setPaperReferences($this->graphB['references'])
            ->getScores();

        $this->assertEquals($expected, $scores, 'The PageRankBase scores do not match');
    }
}
