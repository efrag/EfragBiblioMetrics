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

namespace Efrag\Lib\BiblioMetrics\Metric\Paper\PageRank;

/**
 * Class PageRankNormalized
 * @package Efrag\Lib\BiblioMetrics\Metric\Paper\PageRank
 */
class PageRankNormalized extends PageRankMetric
{

    /**
     * This method needs to be implemented in the subclasses and defines how we are calculating the summary score for
     * each publication included in the Paper-Citation graph.
     * @param string|int $paperId The current paper identifier e.g. 5
     * @param array $prevPageRank The array that holds the previous PageRank values
     * @param array $params Array with any extra parameters required to calculate the score
     * @return float|int
     */
    protected function sumScore($paperId, array $prevPageRank, array $params = [])
    {
        $sinkPR = $params['sink'];

        $score = 0;

        foreach ($this->paperCitations[$paperId] as $gen1) {
            $numReferences = count($this->paperReferences[$gen1]);

            if ($numReferences > 0) {
                $score += $prevPageRank[$gen1] / $numReferences;
            }
        }

        $score += $sinkPR / count($this->paperCitations);

        return $score;
    }

    /**
     * This method will be implemented in the subclasses to get which constant should be used as the random hop
     * @return float
     */
    protected function getRandomHop()
    {
        $numPapers = count($this->paperCitations);

        return (1 - $this->factor) / $numPapers;
    }

    /**
     * @param $previousPageRank
     * @return int
     */
    protected function getSumOfSinkNodes($previousPageRank)
    {
        $sinkNodesPR = 0;

        foreach ($this->paperReferences as $paper => $references) {
            if (count($references) == 0) {
                $sinkNodesPR += $previousPageRank[$paper];
            }
        }

        return $sinkNodesPR;
    }

    /**
     * @param array $previousPageRank
     * @return array
     */
    protected function calculateExtraParams(array $previousPageRank)
    {
        return ['sink' => $this->getSumOfSinkNodes($previousPageRank)];
    }
}