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

trait GraphA {
    /**
     * @var array
     */
    protected $graphA = [
        'citations' => [
            1 => [2, 3, 4, 6, 7, 10],
            2 => [6],
            3 => [5],
            4 => [5],
            5 => [9],
            6 => [],
            7 => [1],
            8 => [1],
            9 => [],
            10 => [8],
        ],
        'references' => [
            1 => [7, 8],
            2 => [1],
            3 => [1],
            4 => [1],
            5 => [3, 4],
            6 => [1, 2],
            7 => [1],
            8 => [10],
            9 => [5],
            10 => [1],
        ]
    ];
}
