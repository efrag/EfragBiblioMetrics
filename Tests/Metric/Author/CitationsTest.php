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

use Efrag\Lib\BiblioMetrics\Metric\Author\Citations;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphC;

/**
 * Class CitationsTest
 * @package Efrag\Lib\BiblioMetrics\Tests\Metric\Author
 */
class CitationsTest extends \PHPUnit_Framework_TestCase
{
    use GraphC;

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingInvalidDecimalPlaces()
    {
        $metric = new Citations();
        $metric->setDecimalPlaces(true);
    }

    public function testSettingValidDecimalPlaces()
    {
        $metric = new Citations();
        $returned = $metric->setDecimalPlaces(19);

        $this->assertTrue($returned instanceof Citations);
    }

    /**
     * @expectedException \Exception
     */
    public function testNotInitializedAuthorPapers()
    {
        $paperCitations = [1 => [2], 2 => []];

        $metric = new Citations();
        $metric->setPaperCitations($paperCitations)->getScores();
    }

    /**
     * @expectedException \Exception
     */
    public function testNotInitializedPaperCitations()
    {
        $authorPapers = [1 => [1, 2], 2 => [2]];

        $metric = new Citations();
        $metric->setAuthorPapers($authorPapers)->getScores();
    }

    public function testScoresGraphC()
    {
        $expectedValues = [
            1 => 5,
            2 => 6,
            3 => 2,
            4 => 2,
            5 => 0
        ];

        $metric = new Citations();
        $scores = $metric
            ->setPaperCitations($this->graphC['citations'])
            ->setAuthorPapers($this->graphC['author_papers'])
            ->getScores();

        $this->assertEquals($expectedValues, $scores, 'The scores do not match the expected values');
    }
}
