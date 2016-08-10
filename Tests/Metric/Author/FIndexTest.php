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

use Efrag\Lib\BiblioMetrics\Metric\Author\FIndex;
use Efrag\Lib\BiblioMetrics\Metric\Author\HIndex;
use Efrag\Lib\BiblioMetrics\Metric\Paper\FValue;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphC;

class FIndexTest extends \PHPUnit_Framework_TestCase
{
    use GraphC;

    public function testScoresGraphC()
    {
        $expectedValues = [
            1 => 0.6201166180758,
            2 => 0.77862001943635,
            3 => 0.35860058309038,
            4 => 0.33965014577259,
            5 => 0.038461538461538
        ];

        $paperMetric = new FValue();
        $paperScores = $paperMetric->setPaperCitations($this->graphC['citations'])->getScores();

        $authorMetric = new FIndex();
        $scores = $authorMetric
            ->setPaperCitations($this->graphC['citations'])
            ->setAuthorPapers($this->graphC['author_papers'])
            ->setPaperAge($this->graphC['age'])
            ->setPaperAuthors($this->graphC['paper_authors'])
            ->setFValueScores($paperScores)
            ->getScores();

        $this->assertEquals($expectedValues, $scores, 'The scores do not match the expected values');
    }
}
