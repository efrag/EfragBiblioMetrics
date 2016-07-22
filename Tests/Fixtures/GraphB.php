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

namespace Efrag\Lib\BiblioMetrics\Tests\Fixtures;

trait GraphB {
    /**
     * @var array
     */
    protected $graphB = [
        'citations' => [
            1 => [2, 3, 4],
            2 => [5],
            3 => [6],
            4 => [7],
            5 => [8, 9, 10],
            6 => [],
            7 => [11, 12, 13],
            8 => [],
            9 => [14, 15, 16, 17, 18, 19, 20, 21, 22],
            10 => [],
            11 => [],
            12 => [],
            13 => [],
            14 => [],
            15 => [],
            16 => [],
            17 => [],
            18 => [],
            19 => [],
            20 => [],
            21 => [],
            22 => [],
        ],
        'references' => [
            1 => [],
            2 => [1],
            3 => [1],
            4 => [1],
            5 => [2],
            6 => [3],
            7 => [4],
            8 => [5],
            9 => [5],
            10 => [5],
            11 => [7],
            12 => [7],
            13 => [7],
            14 => [9],
            15 => [9],
            16 => [9],
            17 => [9],
            18 => [9],
            19 => [9],
            20 => [9],
            21 => [9],
            22 => [9],
        ],
        'age' => [
            1 => 4,
            2 => 4,
            3 => 4,
            4 => 4,
            5 => 3,
            6 => 3,
            7 => 3,
            8 => 2,
            9 => 2,
            10 => 2,
            11 => 2,
            12 => 2,
            13 => 2,
            14 => 1,
            15 => 1,
            16 => 1,
            17 => 1,
            18 => 1,
            19 => 1,
            20 => 1,
            21 => 1,
            22 => 1,
        ]
    ];
}
