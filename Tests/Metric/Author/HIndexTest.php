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

use Efrag\Lib\BiblioMetrics\Metric\Author\HIndex;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphC;

/**
 * Class HIndexTest
 * @package Efrag\Lib\BiblioMetrics\Tests\Metric\Author
 */
class HIndexTest extends \PHPUnit_Framework_TestCase
{
    use GraphC;

    public function testScoresGraphC()
    {
        $expectedValues = [
            1 => 2,
            2 => 2,
            3 => 1,
            4 => 1,
            5 => 0,
        ];

        $metric = new HIndex();
        $scores = $metric
            ->setPaperCitations($this->graphC['citations'])
            ->setAuthorPapers($this->graphC['author_papers'])
            ->getScores();

        $this->assertEquals($expectedValues, $scores, 'The scores do not match the expected values');
    }
}
