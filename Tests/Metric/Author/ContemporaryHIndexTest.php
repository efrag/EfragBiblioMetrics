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

use Efrag\Lib\BiblioMetrics\Metric\Author\ContemporaryHIndex;
use Efrag\Lib\BiblioMetrics\Tests\Fixtures\GraphC;

/**
 * Class ContemporaryHIndexTest
 * @package Efrag\Lib\BiblioMetrics\Tests\Metric\Author
 */
class ContemporaryHIndexTest extends \PHPUnit_Framework_TestCase
{
    use GraphC;

    public function ContemporaryHIndexCases()
    {
        $cases = [];

        $cases[] = [
            4,
            ['p1' => ['p2'], 'p2' => ['p3', 'p4'], 'p3' => [], 'p4' => []],
            ['p1' => 2016 - 2014 + 1, 'p2' => 2016 - 2014 + 1, 'p3' => 2016 - 2015 + 1, 'p4' => 2016 - 2016 + 1],
            ['a1' => ['p1'], 'a2' => ['p1', 'p2'], 'a3' => ['p3', 'p4']],
            ['a1' => 1, 'a2' => 1, 'a3' => 0]
        ];

        return $cases;
    }

    /**
     * @dataProvider ContemporaryHIndexCases
     *
     * @param $gama
     * @param $paperCitations
     * @param $paperAge
     * @param $authorPapers
     * @param $authorScores
     */
    public function testScoresGraphC($gama, $paperCitations, $paperAge, $authorPapers, $authorScores)
    {

        $metric = new ContemporaryHIndex();
        $scores = $metric
            ->setGama($gama)
            ->setPaperCitations($paperCitations)
            ->setAuthorPapers($authorPapers)
            ->setPaperAge($paperAge)
            ->getScores();

        $this->assertEquals($authorScores, $scores, 'The scores do not match the expected values');
    }
}
