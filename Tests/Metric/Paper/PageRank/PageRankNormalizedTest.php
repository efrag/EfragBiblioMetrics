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

namespace Efrag\Lib\BiblioMetrics\Tests\Metric\Paper\PageRank;

use Efrag\Lib\BiblioMetrics\Metric\Paper\PageRank\PageRankNormalized;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphA;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphB;

/**
 * Class PageRankNormalizedTest
 * @package Efrag\Lib\BiblioMetrics\Tests\Metric\Paper\PageRank
 */
class PageRankNormalizedTest extends \PHPUnit_Framework_TestCase
{
    use GraphA;
    use GraphB;

    public function testPageRankNormalizedGraphA()
    {
        $expected = [
            1 => 0.36610006621323,
            2 => 0.021375,
            3 => 0.02679375,
            4 => 0.02679375,
            5 => 0.02775,
            6 => 0.015,
            7 => 0.17059208203044,
            8 => 0.17059208203044,
            9 => 0.015,
            10 => 0.16000326972588,
        ];

        $metric = new PageRankNormalized();
        $scores = $metric
            ->setPaperCitations($this->graphA['citations'])
            ->setPaperReferences($this->graphA['references'])
            ->getScores();

        $this->assertEquals($expected, $scores, 'The PageRankNormalized scores do not match');
    }

    public function testPageRankNormalizedGraphB()
    {
        $expected = [
            1 => 0.21119027750949,
            2 => 0.14295585206272,
            3 => 0.027709230524991,
            4 => 0.060172910791111,
            5 => 0.15056203832218,
            6 => 0.01497748592064,
            7 => 0.05317034270852,
            8 => 0.01497748592064,
            9 => 0.12955552090379,
            10 => 0.01497748592064,
            11 => 0.01497748592064,
            12 => 0.01497748592064,
            13 => 0.01497748592064,
            14 => 0.01497748592064,
            15 => 0.01497748592064,
            16 => 0.01497748592064,
            17 => 0.01497748592064,
            18 => 0.01497748592064,
            19 => 0.01497748592064,
            20 => 0.01497748592064,
            21 => 0.01497748592064,
            22 => 0.01497748592064,
         ];

        $metric = new PageRankNormalized();
        $scores = $metric
            ->setPaperCitations($this->graphB['citations'])
            ->setPaperReferences($this->graphB['references'])
            ->getScores();

        $this->assertEquals($expected, $scores, 'The PageRankNormalized scores do not match');
    }
}