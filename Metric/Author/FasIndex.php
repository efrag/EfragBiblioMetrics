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
 * Class FasIndex
 * @package Efrag\Lib\BiblioMetrics\Metric\Author
 */
class FasIndex extends AuthorMetric
{

    /**
     * The number of Top papers to include in the calculations
     * @var int
     */
    protected $topPapers = 25;

    /**
     * Whether we wish to include all the papers in the Publication Record of an author or just the top papers
     * @var bool
     */
    protected $useTopPapers = false;

    /**
     * Specify the depth for which we would want to calculate the fa-index for.
     * @var int
     */
    protected $depth = 3;

    /**
     * An array indexed using the paperId with values equal to the fp-index array for the values up to the depth
     * specified.
     * @var array
     */
    protected $paperScores;

    /**
     * Setter method for the top papers included in the calculations
     * @param int $topPapers
     * @return $this
     * @throws \Exception
     */
    public function setTopPapers($topPapers)
    {
        if (!is_int($topPapers) || $topPapers < 0) {
            throw new \Exception("Number of top papers to use must be a positive integer value");
        }

        $this->topPapers = $topPapers;

        return $this;
    }

    /**
     * Setter method that enables the use of only the top papers for the calculations of the PageRank score
     * @return $this
     */
    public function useTopPapersOnly()
    {
        $this->useTopPapers = true;

        return $this;
    }

    /**
     * Setter method used to provide the array of fp-index values for the papers included in the Paper-Citation graph
     * @param array $paperScores
     * @return $this
     */
    public function setPaperScores(array $paperScores)
    {
        $this->paperScores = $paperScores;
        return $this;
    }

    /**
     * Setter method for the depth included in the calculations
     * @param int $depth
     * @return $this
     * @throws \Exception
     */
    public function setDepth($depth)
    {
        if (!is_int($depth) || $depth < 0) {
            throw new \Exception("The Depth should be a positive integer value");
        }

        $this->depth = $depth;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInitialized()
    {
        return
            isset($this->paperCitations) &&
            isset($this->authorPapers) &&
            (!$this->useTopPapers || ($this->useTopPapers === true && isset($this->topPapers))) &&
            (isset($this->paperScores) && count($this->paperScores) == count($this->paperCitations))
            ;
    }

    /**
     * @return array
     */
    protected function generateScores()
    {
        $scores = [];

        for ($depth = 1; $depth <= $this->depth; $depth++) {
            foreach ($this->authorPapers as $authorId => $papers) {
                $numPapers = count($papers);

                $scores[$authorId][$depth] = 0;

                if (!$this->useTopPapers || $numPapers <= $this->topPapers) {
                    foreach ($papers as $paperId) {
                        $scores[$authorId][$depth] += $this->paperScores[$paperId][$authorId][$depth];
                    }
                } else {
                    $authorPaperScores = [];
                    $numPapers = $this->topPapers;

                    foreach ($papers as $paperId) {
                        $authorPaperScores[] = $this->paperScores[$paperId][$authorId][$depth];
                    }
                    rsort($authorPaperScores);

                    for ($i = 0; $i < $numPapers; $i++) {
                        $scores[$authorId][$depth] += $authorPaperScores[$i];
                    }
                }

                $scores[$authorId][$depth] = $scores[$authorId][$depth] / $numPapers;
            }
        }

        return $scores;
    }
}
