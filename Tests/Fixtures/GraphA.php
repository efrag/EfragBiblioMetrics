<?php

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
