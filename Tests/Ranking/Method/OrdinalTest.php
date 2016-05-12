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

namespace Efrag\Lib\BiblioMetrics\Tests\Ranking\Method;

use Efrag\Lib\BiblioMetrics\Ranking\Method\Ordinal;

class OrdinalTest extends \PHPUnit_Framework_TestCase
{
    public function OrdinalRankingCases()
    {
        $cases = [];

        $cases[] = [
            ['a' => 150, 'b' => 150, 'c' => 130, 'd' => 130, 'e' => 130, 'f' => 120],
            ['a' => 1.5, 'b' => 1.5, 'c' => 4, 'd' => 4, 'e' => 4, 'f' => 6]
        ];

        $cases[] = [
            ['a' => 150, 'b' => 150, 'c' => 130, 'd' => 130, 'e' => 120, 'f' => 120],
            ['a' => 1.5, 'b' => 1.5, 'c' => 3.5, 'd' => 3.5, 'e' => 5.5, 'f' => 5.5]
        ];

        return $cases;
    }

    /**
     * @dataProvider OrdinalRankingCases
     *
     * @param $entityValue
     * @param $entityRanking
     */
    public function testOrdinal($entityValue, $entityRanking)
    {
        $ordinal = new Ordinal();
        $ranks = $ordinal->setEntityValue($entityValue)->getRanking();

        $this->assertEquals($entityRanking, $ranks, 'The rankings do not match');
    }
}
