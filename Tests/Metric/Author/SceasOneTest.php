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

namespace Efrag\Lib\BiblioMetrics\Tests\Metric\Author;

use Efrag\Lib\BiblioMetrics\Metric\Author\SceasMetric;
use Efrag\Lib\BiblioMetrics\Metric\Paper\Sceas\SceasOneScore;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphC;

/**
 * Class SceasOneTest
 * @package Efrag\Lib\BiblioMetrics\Tests\Metric\Author
 */
class SceasOneTest extends \PHPUnit_Framework_TestCase
{
    use GraphC;

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingInvalidTopPapers()
    {
        $metric = new SceasMetric();
        $metric->setTopPapers('test');
    }

    public function testScoresGraphCCases()
    {
        $cases = [];

        // Test case when using the top 25 papers for each author
        $cases[] = [
            25,
            [
                1 => 0.64438085001005396,
                2 => 0.55464599833037465,
                3 => 0.1564604292015507,
                4 => 0.28544144329133631,
                5 => 0
            ]
        ];

        // Test case when using the top paper for each author
        $cases[] = [
            1,
            [
                1 => 1.3813230170902,
                2 => 1.3813230170902,
                3 => 0.28544144329134,
                4 => 0.28544144329134,
                5 => 0,
            ]
        ];

        return $cases;
    }

    /**
     * @dataProvider testScoresGraphCCases
     */
    public function testScoresGraphC($topPapers, $expectedValues)
    {
        $paperMetric = new SceasOneScore();
        $paperScores = $paperMetric
            ->setPaperReferences($this->graphC['references'])
            ->setPaperCitations($this->graphC['citations'])
            ->setMaxIterations(30000)
            ->setFactorA(2.71828)
            ->setFactorB(1)
            ->getScores();

        $metric = new SceasMetric();
        $scores = $metric
            ->setPaperCitations($this->graphC['citations'])
            ->setAuthorPapers($this->graphC['author_papers'])
            ->setTopPapers($topPapers)
            ->setPaperScores($paperScores)
            ->getScores();

        $this->assertEquals($expectedValues, $scores, 'The scores do not match the expected values');
    }
}
