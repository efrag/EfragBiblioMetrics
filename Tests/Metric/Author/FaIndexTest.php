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

use Efrag\Lib\BiblioMetrics\Metric\Author\FaIndex;
use Efrag\Lib\BiblioMetrics\Metric\Paper\FpIndex\FpIndexMetric;
use Efrag\Lib\BiblioMetrics\MSO\MSOGsDefinition;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphC;
use Efrag\Lib\BiblioMetrics\Utils\GraphProcessing;

class FaIndexTest extends \PHPUnit_Framework_TestCase
{
    use GraphC;

    /**
     * @expectedException \Exception
     */
    public function testSettingTopPapers()
    {
        $authorMetric = new FaIndex();
        $authorMetric->setTopPapers('test');
    }

    /**
     * @expectedException \Exception
     */
    public function testSettingDepth()
    {
        $authorMetric = new FaIndex();
        $authorMetric->setDepth('test');
    }

    /**
     * The cases provided have 4 elements (useTopPapers, numTopPapers, depth, expectedOutcome)
     * @return array
     */
    public function FaIndexCases()
    {
        $cases = [];

        // Only consider each author's top paper
        $cases[] = [
            false,
            null,
            3,
            [
                1 => [
                    1 => 0.19786324786325,
                    2 => 0.22008547008547,
                    3 => 0.22749287749288,
                ],
                2 => [
                    1 => 0.18760822510823,
                    2 => 0.22213203463203,
                    3 => 0.22768759018759,
                ],
                3 => [
                    1 => 0.12554112554113,
                    2 => 0.14935064935065,
                    3 => 0.14935064935065,
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
                    2 => 0.33333333333333,
                    3 => 0.35555555555556,
                ],
                2 => [
                    1 => 0.26666666666667,
                    2 => 0.33333333333333,
                    3 => 0.35555555555556,
                ],
                3 => [
                    1 => 0.14285714285714,
                    2 => 0.21428571428571,
                    3 => 0.21428571428571,
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
     * @dataProvider FaIndexCases
     */
    public function testFaIndex($useTopPapers, $topPapers, $depth, $expected)
    {

        $mso = new MSOGsDefinition();
        $mso->setDepth($depth)->setPaperCitations($this->graphC['citations']);

        $paperMetric = new FpIndexMetric();
        $paperScores = $paperMetric
            ->setDepth($depth)
            ->setPaperCitations($this->graphC['citations'])
            ->setPaperYear(GraphProcessing::generatePublicationYear($this->graphC['age']))
            ->setMSOGs($mso->getMSO())
            ->getScores();

        $authorMetric = new FaIndex();
        $authorMetric
            ->setPaperCitations($this->graphC['citations'])
            ->setAuthorPapers($this->graphC['author_papers'])
            ->setPaperScores($paperScores)
            ->setDepth($depth);

        if ($useTopPapers) {
            $authorMetric->useTopPapersOnly()->setTopPapers($topPapers);
        }

        $this->assertEquals($expected, $authorMetric->getScores(), 'The FaIndex scores do not match');
    }
}
