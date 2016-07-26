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
 * Class GIndex
 * @package Efrag\Lib\BiblioMetrics\Metric\Author
 */
class GIndex extends AuthorMetric
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
        $scores = array();

        foreach ($this->authorPapers as $authorId => $papers) {
            $tmpIndex = 0;
            $citationCounts = [];

            // create an array with all of the author's papers that have a non-zero citation count
            foreach ($papers as $paperId) {
                $pCount = count($this->paperCitations[$paperId]);
                if ($pCount > 0) {
                    $citationCounts[] = count($this->paperCitations[$paperId]);
                }
            }

            if (count($citationCounts) > 0) {
                // if the author has any papers with non-zero citations then sort them by the number of citations
                // so that we can start the calculations for g-index. The plan would be to sort the citations in
                // descending order and then start adding them up so that the strongest papers are included since
                // they will be contributing more towards the g-index calculations
                rsort($citationCounts);

                $numPapers = count($citationCounts);

                // g-index is defined as the largest number g of papers that together have received g^2 citations
                // So to get the combined number of citations we loop through the citation counts
                $citationTotals = [];
                for ($i = 0; $i < $numPapers; $i++) {
                    if ($i == 0) {
                        $citationTotals[$i+1] = $citationCounts[$i];
                    } else {
                        $citationTotals[$i+1] = $citationTotals[$i] + $citationCounts[$i];
                    }
                }

                foreach ($citationTotals as $numPapers => $total) {
                    if ($numPapers * $numPapers <= $total) {
                        $tmpIndex = $numPapers;
                    }
                }
            }

            $scores[$authorId] = $tmpIndex;
        }

        return $scores;
    }
}
