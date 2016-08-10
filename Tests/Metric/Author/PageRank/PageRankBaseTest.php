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

use Efrag\Lib\BiblioMetrics\Metric\Author\PageRankMetric;
use Efrag\Lib\BiblioMetrics\Metric\Paper\PageRank\PageRankBase;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphC;

/**
 * Class PageRankBaseTest
 * @package Efrag\Lib\BiblioMetrics\Tests\Metric\Author\PageRank
 */
class PageRankBaseTest extends \PHPUnit_Framework_TestCase
{
    use GraphC;

    /**
     * @expectedException \Exception
     */
    public function testSettingTopPapers()
    {
        $authorMetric = new PageRankMetric();
        $authorMetric->setTopPapers('test');
    }

    /**
     * The cases provided have 3 elements (useTopPapers, numTopPapers, expectedOutcome)
     * @return array
     */
    public function PageRankBaseCases()
    {
        $cases = [];

        // Only consider each author's top paper
        $cases[] = [
            false,
            null,
            [
                1 => 0.441496875,
                2 => 0.40488046875,
                3 => 0.21959375,
                4 => 0.29503125,
                5 => 0.15
            ]
        ];

        $cases[] = [
            true,
            1,
            [
                1 => 0.833240625,
                2 => 0.833240625,
                3 => 0.29503125,
                4 => 0.29503125,
                5 => 0.15
            ]
        ];

        return $cases;
    }

    /**
     * @param $useTopPapers
     * @param $topPapers
     * @param $expected
     *
     * @dataProvider PageRankBaseCases
     */
    public function testPageRankBase($useTopPapers, $topPapers, $expected)
    {
        $paperMetric = new PageRankBase();
        $paperScores = $paperMetric
            ->setPaperCitations($this->graphC['citations'])
            ->setPaperReferences($this->graphC['references'])
            ->getScores();

        $authorMetric = new PageRankMetric();
        $authorMetric
            ->setAuthorPapers($this->graphC['author_papers'])
            ->setPaperCitations($this->graphC['citations'])
            ->setPageRankScores($paperScores)
        ;

        if ($useTopPapers) {
            $authorMetric->useTopPapersOnly()->setTopPapers($topPapers);
        }

        $this->assertEquals($expected, $authorMetric->getScores(), 'The PageRankBase scores do not match');
    }
}
