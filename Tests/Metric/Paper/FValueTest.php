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

namespace Efrag\Lib\BiblioMetrics\Tests\Metric\Paper;

use Efrag\Lib\BiblioMetrics\Metric\Paper\FValue;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphA;

class FValueTest extends \PHPUnit_Framework_TestCase
{
    use GraphA;

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
        $metric = new FValue();
        $maxIterations = $metric->setMaxIterations($iterations)->getMaxIterations();

        $this->assertEquals($expected, $maxIterations, 'Not matching Max Iteration values');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotNumericMaxIterations()
    {
        $metric = new FValue();
        $metric->setMaxIterations('test');
    }

    public function testFValue()
    {
        $metric = new FValue();
        $scores = $metric->setPaperCitations($this->graphA['citations'])->getScores();

        $expected = [
            1 => 23.571605447792,
            2 => 1.65,
            3 => 2.0725,
            4 => 2.0725,
            5 => 1.65,
            6 => 1,
            7 => 16.321543541065,
            8 => 16.321543541065,
            9 => 1,
            10 => 11.609003301692,
        ];

        $this->assertEquals($expected, $scores, 'The f-values do not match');
    }
}
