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

namespace Efrag\Lib\BiblioMetrics\Metric\Author;

use Efrag\Lib\BiblioMetrics\Metric\AuthorMetric;

/**
 * Class HIndex
 * @package Efrag\Lib\BiblioMetrics\Metric\Author
 */
class HIndex extends AuthorMetric
{

    /**
     * @return bool
     */
    public function isInitialized()
    {
        return isset($this->paperCitations) && isset($this->authorPapers);
    }

    /**
     * @return array
     */
    protected function generateScores()
    {
        $scores = [];

        foreach ($this->authorPapers as $authorId => $papers) {
            $numPapers = count($papers);

            // Create an array that has as many elements as the papers this author has written. The h-index has
            // as an upper limit the number of papers an author has written anyway
            $citationCounts = array_fill(0, $numPapers + 1, 0);

            // loop through the papers the author has written and add it to the citationCounts array. If an author
            // has written three papers then he will have an array with keys 0,1,2,3 and if a paper has 2 citations
            // we are going to add 1 to the element with key = 2. If a paper has 15 citations we will add 1 to the
            // element with key = 3 (the largest one)
            foreach ($papers as $paperId) {
                $paperCitations = count($this->paperCitations[$paperId]);

                $topElement = ($paperCitations > $numPapers) ? $numPapers : $paperCitations;
                for ($j = 1; $j <= $topElement; $j++) {
                    $citationCounts[$j] += 1;
                }
            }

            $tmpIndex = 0;
            // now loop through the citationCounts array and see what is the h-index
            for ($i = 0; $i <= $numPapers; $i++) {
                if ($citationCounts[$i] >= $i) {
                    $tmpIndex = $i;
                }
            }

            $scores[$authorId] = $tmpIndex;
        }

        return $scores;
    }
}
