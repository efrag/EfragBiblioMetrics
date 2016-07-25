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

use Efrag\Lib\BiblioMetrics\Metric\Author\AverageCitations;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphC;

/**
 * Class AverageCitationsTest
 * @package Efrag\Lib\BiblioMetrics\Tests\Metric\Author
 */
class AverageCitationsTest extends \PHPUnit_Framework_TestCase
{
    use GraphC;

    public function testScoresGraphC()
    {
        $expectedValues = [
            1 => 1.6666666666666667,
            2 => 1.5,
            3 => 0.66666666666666663,
            4 => 1,
            5 => 0,
        ];

        $metric = new AverageCitations();
        $scores = $metric
            ->setPaperCitations($this->graphC['citations'])
            ->setAuthorPapers($this->graphC['author_papers'])
            ->getScores();

        $this->assertEquals($expectedValues, $scores, 'The scores do not match the expected values');
    }
}
