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

namespace Efrag\Lib\BiblioMetrics\Tests\Metric\Paper\FpIndex;

use Efrag\Lib\BiblioMetrics\Metric\Paper\FpIndex\FpIndexMetric;
use Efrag\Lib\BiblioMetrics\MSO\MSOGsDefinition;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphA;
use Efrag\Lib\BiblioMetrics\Utils\GraphProcessing;

class FpIndexMetricTest extends \PHPUnit_Framework_TestCase
{
    use GraphA;

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingInvalidDepth()
    {
        $metric = new FpIndexMetric();
        $metric->setDepth('test');
    }

    /**
     * @return array
     */
    public function depthCases()
    {
        $cases = [];

        $cases[] = [3, 3];
        $cases[] = [4.4, 4];
        $cases[] = [5.55, 5];

        return $cases;
    }

    /**
     * @dataProvider depthCases
     */
    public function testSettingDepth($depth, $expected)
    {
        $metric = new FpIndexMetric();
        $setDepth = $metric->setDepth($depth)->getDepth();

        $this->assertEquals($expected, $setDepth, 'Not matching Depth values');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Metric class has not been initialized properly.
     */
    public function testInitializedPaperYear()
    {
        $mso = new MSOGsDefinition();
        $mso->setDepth(3)->setPaperCitations($this->graphA['citations']);

        $metric = new FpIndexMetric();
        $metric->setDepth(3)->setPaperCitations($this->graphA['citations'])->setMSOGs($mso->getMSO())->getScores();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Metric class has not been initialized properly.
     */
    public function testInitializedPaperCitations()
    {
        $mso = new MSOGsDefinition();
        $mso->setDepth(3)->setPaperCitations($this->graphA['citations']);

        $metric = new FpIndexMetric();
        $metric
            ->setDepth(3)
            ->setMSOGs($mso->getMSO())
            ->setPaperYear(GraphProcessing::generatePublicationYear($this->graphA['age']))
            ->getScores();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Metric class has not been initialized properly.
     */
    public function testInitializedMSOGs()
    {
        $metric = new FpIndexMetric();
        $metric
            ->setDepth(3)
            ->setPaperCitations($this->graphA['citations'])
            ->setPaperYear(GraphProcessing::generatePublicationYear($this->graphA['age']))
            ->getScores();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessageRegExp "^PaperId:\s[0-9]+:\sno publication year$"
     */
    public function testMissingPaperYear()
    {
        $mso = new MSOGsDefinition();
        $mso->setDepth(3)->setPaperCitations($this->graphA['citations']);

        $pubYears = GraphProcessing::generatePublicationYear($this->graphA['age']);

        list($paperId, $year) = each($pubYears); // get the first element so that we can unset it
        unset($pubYears[$paperId]);
        $pubYears['test'] = 1; // required to maintain the number of elements in the arrays the same

        $metric = new FpIndexMetric();
        $metric
            ->setDepth(3)
            ->setPaperCitations($this->graphA['citations'])
            ->setPaperYear($pubYears)
            ->setMSOGs($mso->getMSO())
            ->getScores();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessageRegExp "PaperId:\s[0-9]+:\sinvalid publication year"
     */
    public function testInvalidYear()
    {
        $mso = new MSOGsDefinition();
        $mso->setDepth(3)->setPaperCitations($this->graphA['citations']);

        $pubYears = GraphProcessing::generatePublicationYear($this->graphA['age']);
        list($paperId, $year) = each($pubYears); // get the first element so that we can unset it
        $pubYears[$paperId] = null;

        $metric = new FpIndexMetric();
        $metric
            ->setDepth(3)
            ->setPaperCitations($this->graphA['citations'])
            ->setPaperYear($pubYears)
            ->setMSOGs($mso->getMSO())
            ->getScores();
    }

    public function testFpIndexMetric()
    {
        $expected = [
            1 => [1 => 7, 2 => 8, 3 => 8.3333333333333],
            2 => [1 => 2, 2 => 2, 3 => 2],
            3 => [1 => 2, 2 => 2.5, 3 => 2.5],
            4 => [1 => 2, 2 => 2.5, 3 => 2.5],
            5 => [1 => 2, 2 => 2, 3 => 2],
            6 => [1 => 1, 2 => 1, 3 => 1],
            7 => [1 => 2, 2 => 4.5, 3 => 5.1666666666667],
            8 => [1 => 2, 2 => 5, 3 => 5.3333333333333],
            9 => [1 => 1, 2 => 1, 3 => 1],
            10 => [1 => 2, 2 => 2.5, 3 => 4.1666666666667],
        ];

        $mso = new MSOGsDefinition();
        $mso->setDepth(3)->setPaperCitations($this->graphA['citations']);

        $metric = new FpIndexMetric();
        $metric
            ->setDepth(3)
            ->setPaperCitations($this->graphA['citations'])
            ->setPaperYear(GraphProcessing::generatePublicationYear($this->graphA['age']))
            ->setMSOGs($mso->getMSO());

        $scores = $metric->getScores();

        $this->assertEquals($expected, $scores, 'The generated fp^3-index values do not match');
    }
}