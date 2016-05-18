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

namespace Efrag\Lib\BiblioMetrics\Tests\Utils;

use Efrag\Lib\BiblioMetrics\Utils\GraphProcessing;

class GraphProcessingTest extends \PHPUnit_Framework_TestCase
{
    public function testGeneratePublicationYear()
    {
        $paperAge = [
            1 => 2,
            2 => 5,
            3 => 10
        ];

        $curYear = 2016;

        $expected = [
            1 => 2015,
            2 => 2012,
            3 => 2007
        ];

        $this->assertEquals($expected, GraphProcessing::generatePublicationYear($paperAge, $curYear));
    }
}