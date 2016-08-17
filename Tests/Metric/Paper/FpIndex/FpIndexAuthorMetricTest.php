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

use Efrag\Lib\BiblioMetrics\Metric\Paper\FpIndex\FpIndexAuthorMetric;
use Efrag\Lib\BiblioMetrics\MSO\MSOAuthorGsDefinition;
use Efrag\Lib\BiblioMetrics\MSO\MSOGsDefinition;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphC;
use Efrag\Lib\BiblioMetrics\Utils\GraphProcessing;

/**
 * Class FpIndexAuthorMetricTest
 * @package Efrag\Lib\BiblioMetrics\Tests\Metric\Paper\FpIndex
 */
class FpIndexAuthorMetricTest extends \PHPUnit_Framework_TestCase
{
    use GraphC;

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingInvalidDepth()
    {
        $metric = new FpIndexAuthorMetric();
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
        $metric = new FpIndexAuthorMetric();
        $setDepth = $metric->setDepth($depth)->getDepth();

        $this->assertEquals($expected, $setDepth, 'Not matching Depth values');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Paper metric class has not been initialized properly.
     */
    public function testInitializedPaperYear()
    {
        $mso = new MSOAuthorGsDefinition();
        $mso
            ->setDepth(3)
            ->setPaperCitations($this->graphC['citations'])
            ->setPaperAuthors($this->graphC['paper_authors']);

        $metric = new FpIndexAuthorMetric();
        $metric
            ->setDepth(3)
            ->setPaperCitations($this->graphC['citations'])
            ->setPaperAuthors($this->graphC['paper_authors'])
            ->setMSOAuthorGs($mso->getMSO())
            ->getScores();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Paper metric class has not been initialized properly.
     */
    public function testInitializedPaperCitations()
    {
        $mso = new MSOAuthorGsDefinition();
        $mso
            ->setDepth(3)
            ->setPaperCitations($this->graphC['citations'])
            ->setPaperAuthors($this->graphC['paper_authors']);

        $metric = new FpIndexAuthorMetric();
        $metric
            ->setDepth(3)
            ->setMSOAuthorGs($mso->getMSO())
            ->setPaperYear(GraphProcessing::generatePublicationYear($this->graphC['age']))
            ->getScores();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Paper metric class has not been initialized properly.
     */
    public function testInitializedMSOAuthorGs()
    {
        $metric = new FpIndexAuthorMetric();
        $metric
            ->setDepth(3)
            ->setPaperCitations($this->graphC['citations'])
            ->setPaperYear(GraphProcessing::generatePublicationYear($this->graphC['age']))
            ->getScores();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessageRegExp "^PaperId:\s[0-9]+:\sno publication year$"
     */
    public function testMissingPaperYear()
    {
        $mso = new MSOAuthorGsDefinition();
        $mso
            ->setDepth(3)
            ->setPaperCitations($this->graphC['citations'])
            ->setPaperAuthors($this->graphC['paper_authors']);

        $pubYears = GraphProcessing::generatePublicationYear($this->graphC['age']);

        list($paperId, $year) = each($pubYears); // get the first element so that we can unset it
        unset($pubYears[$paperId]);
        $pubYears['test'] = 1; // required to maintain the number of elements in the arrays the same

        $metric = new FpIndexAuthorMetric();
        $metric
            ->setDepth(3)
            ->setPaperCitations($this->graphC['citations'])
            ->setPaperAuthors($this->graphC['paper_authors'])
            ->setPaperYear($pubYears)
            ->setMSOAuthorGs($mso->getMSO())
            ->getScores();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessageRegExp "PaperId:\s[0-9]+:\sinvalid publication year"
     */
    public function testInvalidYear()
    {
        $mso = new MSOAuthorGsDefinition();
        $mso
            ->setDepth(3)
            ->setPaperCitations($this->graphC['citations'])
            ->setPaperAuthors($this->graphC['paper_authors']);

        $pubYears = GraphProcessing::generatePublicationYear($this->graphC['age']);
        list($paperId, $year) = each($pubYears); // get the first element so that we can unset it
        $pubYears[$paperId] = null;

        $metric = new FpIndexAuthorMetric();
        $metric
            ->setDepth(3)
            ->setPaperCitations($this->graphC['citations'])
            ->setPaperAuthors($this->graphC['paper_authors'])
            ->setPaperYear($pubYears)
            ->setMSOAuthorGs($mso->getMSO())
            ->getScores();
    }

    public function testFpAuthorIndexMetric()
    {
        $expected = [
            1 => [
                1 => [1 => 0.26666666666667, 2 => 0.26666666666667, 3 => 0.28888888888889],
                2 => [1 => 0.2, 2 => 0.23333333333333, 3 => 0.23333333333333]
            ],
            2 => [
                3 => [1 => 0.14285714285714, 2 => 0.14285714285714, 3 => 0.14285714285714],
            ],
            3 => [
                3 => [1 => 0.14285714285714, 2 => 0.17857142857143, 3 => 0.17857142857143],
                4 => [1 => 0.14285714285714, 2 => 0.21428571428571, 3 => 0.21428571428571],
            ],
            4 => [
                2 => [1 => 0.071428571428571, 2 => 0.10714285714286, 3 => 0.10714285714286],
                4 => [1 => 0.14285714285714, 2 => 0.21428571428571, 3 => 0.21428571428571],
            ],
            5 => [
                1 => [1 => 0.076923076923077, 2 => 0.076923076923077, 3 => 0.076923076923077],
                5 => [1 => 0.076923076923077, 2 => 0.076923076923077, 3 => 0.076923076923077],
            ],
            6 => [
                1 => [1 => 0.16666666666667, 2 => 0.16666666666667, 3 => 0.16666666666667],
                2 => [1 => 0.16666666666667, 2 => 0.16666666666667, 3 => 0.16666666666667],
            ],
            7 => [
                2 => [1 => 0.090909090909091, 2 => 0.090909090909091, 3 => 0.090909090909091],
                3 => [1 => 0.090909090909091, 2 => 0.090909090909091, 3 => 0.090909090909091],
            ],
        ];

        $mso = new MSOAuthorGsDefinition();
        $mso
            ->setDepth(3)
            ->setPaperCitations($this->graphC['citations'])
            ->setPaperAuthors($this->graphC['paper_authors']);

        $metric = new FpIndexAuthorMetric();
        $metric
            ->setDepth(3)
            ->setMSOAuthorGs($mso->getMSO())
            ->setPaperCitations($this->graphC['citations'])
            ->setPaperAuthors($this->graphC['paper_authors'])
            ->setPaperYear(GraphProcessing::generatePublicationYear($this->graphC['age']));

        $scores = $metric->getScores();

        $this->assertEquals($expected, $scores, 'The generated fp^3-index author values do not match');
    }
}