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

namespace Efrag\Lib\BiblioMetrics\Tests\Metric\Author\Sceas;

use Efrag\Lib\BiblioMetrics\Metric\Author\SceasMetric;
use Efrag\Lib\BiblioMetrics\Metric\Paper\Sceas\SceasTwoScore;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphC;

/**
 * Class SceasTwoTest
 * @package Efrag\Lib\BiblioMetrics\Tests\Metric\Author\Sceas
 */
class SceasTwoTest extends \PHPUnit_Framework_TestCase
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
                1 => 0.76320795922642,
                2 => 0.67594005413659,
                3 => 0.29797917889447,
                4 => 0.41413633886713,
                5 => 0.15
            ]
        ];

        // Test case when using the top paper for each author
        $cases[] = [
            1,
            [
                1 => 1.4502202842304,
                2 => 1.4502202842304,
                3 => 0.41413633886713,
                4 => 0.41413633886713,
                5 => 0.15,
            ]
        ];

        return $cases;
    }

    /**
     * @dataProvider testScoresGraphCCases
     */
    public function testScoresGraphC($topPapers, $expectedValues)
    {
        $paperMetric = new SceasTwoScore();
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
