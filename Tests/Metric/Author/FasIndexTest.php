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

namespace Efrag\Lib\BiblioMetrics\Tests\Metric\Author\PageRank;

use Efrag\Lib\BiblioMetrics\Metric\Author\FasIndex;
use Efrag\Lib\BiblioMetrics\Metric\Paper\FpIndex\FpIndexAuthorMetric;
use Efrag\Lib\BiblioMetrics\Metric\Paper\FpIndex\FpIndexMetric;
use Efrag\Lib\BiblioMetrics\MSO\MSOAuthorGsDefinition;
use Efrag\Lib\BiblioMetrics\MSO\MSOGsDefinition;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphC;
use Efrag\Lib\BiblioMetrics\Utils\GraphProcessing;

/**
 * Class FasIndexTest
 * @package Efrag\Lib\BiblioMetrics\Tests\Metric\Author\PageRank
 */
class FasIndexTest extends \PHPUnit_Framework_TestCase
{
    use GraphC;

    /**
     * @expectedException \Exception
     */
    public function testSettingTopPapers()
    {
        $authorMetric = new FasIndex();
        $authorMetric->setTopPapers('test');
    }

    /**
     * @expectedException \Exception
     */
    public function testSettingDepth()
    {
        $authorMetric = new FasIndex();
        $authorMetric->setDepth('test');
    }

    /**
     * The cases provided have 4 elements (useTopPapers, numTopPapers, depth, expectedOutcome)
     * @return array
     */
    public function FasIndexCases()
    {
        $cases = [];

        // Only consider each author's top paper
        $cases[] = [
            false,
            null,
            3,
            [
                1 => [
                    1 => 0.17008547008547,
                    2 => 0.17008547008547,
                    3 => 0.17749287749288,
                ],
                2 => [
                    1 => 0.13225108225108,
                    2 => 0.14951298701299,
                    3 => 0.14951298701299,
                ],
                3 => [
                    1 => 0.12554112554113,
                    2 => 0.13744588744589,
                    3 => 0.13744588744589,
                ],
                4 => [
                    1 => 0.14285714285714,
                    2 => 0.21428571428571,
                    3 => 0.21428571428571,
                ],
                5 => [
                    1 => 0.076923076923077,
                    2 => 0.076923076923077,
                    3 => 0.076923076923077,
                ]
            ]
        ];

        $cases[] = [
            true,
            1,
            3,
            [
                1 => [
                    1 => 0.26666666666667,
                    2 => 0.26666666666667,
                    3 => 0.28888888888889,
                ],
                2 => [
                    1 => 0.2,
                    2 => 0.23333333333333,
                    3 => 0.23333333333333,
                ],
                3 => [
                    1 => 0.14285714285714,
                    2 => 0.17857142857143,
                    3 => 0.17857142857143,
                ],
                4 => [
                    1 => 0.14285714285714,
                    2 => 0.21428571428571,
                    3 => 0.21428571428571,
                ],
                5 => [
                    1 => 0.076923076923077,
                    2 => 0.076923076923077,
                    3 => 0.076923076923077,
                ]
            ]
        ];

        return $cases;
    }

    /**
     * @param $useTopPapers
     * @param $topPapers
     * @param $depth
     * @param $expected
     *
     * @dataProvider FasIndexCases
     */
    public function testFasIndex($useTopPapers, $topPapers, $depth, $expected)
    {

        $mso = new MSOAuthorGsDefinition();
        $mso
            ->setDepth($depth)
            ->setPaperCitations($this->graphC['citations'])
            ->setPaperAuthors($this->graphC['paper_authors']);

        $paperMetric = new FpIndexAuthorMetric();
        $paperScores = $paperMetric
            ->setDepth($depth)
            ->setPaperCitations($this->graphC['citations'])
            ->setPaperAuthors($this->graphC['paper_authors'])
            ->setPaperYear(GraphProcessing::generatePublicationYear($this->graphC['age']))
            ->setMSOAuthorGs($mso->getMSO())
            ->getScores();

        $authorMetric = new FasIndex();
        $authorMetric
            ->setPaperCitations($this->graphC['citations'])
            ->setAuthorPapers($this->graphC['author_papers'])
            ->setPaperScores($paperScores)
            ->setDepth($depth);

        if ($useTopPapers) {
            $authorMetric->useTopPapersOnly()->setTopPapers($topPapers);
        }

        $authorScores = $authorMetric->getScores();

        $this->assertEquals($expected, $authorScores, 'The FasIndex scores do not match');
    }
}
