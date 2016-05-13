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

namespace Efrag\Lib\BiblioMetrics\Tests\MSO;

use Efrag\Lib\BiblioMetrics\MSO\MSOHmDefinition;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphA;

class MSOHmDefinitionTest extends \PHPUnit_Framework_TestCase
{
    use GraphA;

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingInvalidDepth()
    {
        $mso = new MSOHmDefinition();
        $mso->setDepth('test');
    }

    /**
     * @return array
     */
    public function depthCases()
    {
        $cases = [];

        $cases[] = [3, 3];
        $cases[] = [4.4, 4];
        $cases[] = [5.55, 5];

        return $cases;
    }

    /**
     * @dataProvider depthCases
     */
    public function testSettingDepth($depth, $expected)
    {
        $mso = new MSOHmDefinition();
        $setDepth = $mso->setDepth($depth)->getDepth();

        $this->assertEquals($expected, $setDepth, 'Not matching Depth values');
    }

    /**
     * @expectedException \Exception
     */
    public function testNotInitializedClass()
    {
        $mso = new MSOHmDefinition();
        $table = $mso->setDepth(3)->getMSO();
    }

    public function testMSOTable()
    {
        $mso = new MSOHmDefinition();
        $table = $mso->setPaperCitations($this->graphA['citations'])->setDepth(3)->getMSO();

        $expected = [
            1 => [1 => 6, 2 => 5, 3 => 9],
            2 => [1 => 1, 2 => 0, 3 => 0],
            3 => [1 => 1, 2 => 1, 3 => 0],
            4 => [1 => 1, 2 => 1, 3 => 0],
            5 => [1 => 1, 2 => 0, 3 => 0],
            6 => [1 => 0, 2 => 0, 3 => 0],
            7 => [1 => 1, 2 => 6, 3 => 5],
            8 => [1 => 1, 2 => 6, 3 => 5],
            9 => [1 => 0, 2 => 0, 3 => 0],
            10 => [1 => 1, 2 => 1, 3 => 6],
        ];

        $this->assertEquals($expected, $table, 'The MSO table does not match');
    }
}
