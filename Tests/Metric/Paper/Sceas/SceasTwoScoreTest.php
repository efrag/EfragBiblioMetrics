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

namespace Efrag\Lib\BiblioMetrics\Tests\Metric\Paper\Sceas;

use Efrag\Lib\BiblioMetrics\Metric\Paper\Sceas\SceasTwoScore;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphA;

class SceasTwoScoreTest extends \PHPUnit_Framework_TestCase
{
    use GraphA;

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotNumericFactorD()
    {
        $metric = new SceasTwoScore();
        $metric->setFactorD('test');
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
    public function testSettingFactorD($factor, $expected)
    {
        $metric = new SceasTwoScore();
        $factorD = $metric->setFactorD($factor)->getFactorD();

        $this->assertEquals($expected, $factorD, 'Not matching FactorD values', 0.00001);
    }

    public function testSceasTwoScore()
    {
        $expected = [
            1 => 0.48003709736164,
            2 => 0.17345233014995,
            3 => 0.18078582067611,
            4 => 0.18078582067611,
            5 => 0.1969046602999,
            6 => 0.15,
            7 => 0.22505325661032,
            8 => 0.22505325661032,
            9 => 0.15,
            10 => 0.22037364367128
        ];

        $metric = new SceasTwoScore();
        $scores = $metric
            ->setPaperCitations($this->graphA['citations'])
            ->setPaperReferences($this->graphA['references'])
            ->getScores();

        $this->assertEquals($expected, $scores);
    }
}
