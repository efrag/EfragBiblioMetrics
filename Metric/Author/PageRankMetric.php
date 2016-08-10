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
 * Class PageRankMetric
 * @package Efrag\Lib\BiblioMetrics\Metric\Author
 */
class PageRankMetric extends AuthorMetric
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
     * An array indexed using the paperId with values equal to the PageRank score for the paper. This could be either
     * the base version or the normalized one
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
     * Setter method used to provide the array of PageRank paper scores for the current Paper-Citation graph
     * @param array $pageRankScores
     * @return $this
     */
    public function setPageRankScores(array $pageRankScores)
    {
        $this->paperScores = $pageRankScores;

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

        foreach ($this->authorPapers as $authorName => $papers) {
            $authorPageRank = 0;

            if ($this->useTopPapers && count($papers) > $this->topPapers) {
                $authorPaperScores = [];

                foreach ($papers as $paperId) {
                    $authorPaperScores[] = $this->paperScores[$paperId];
                }
                rsort($authorPaperScores);

                for ($i = 0; $i < $this->topPapers; $i++) {
                    $authorPageRank += $authorPaperScores[$i];
                }

                $scores[$authorName] = $authorPageRank / $this->topPapers;
            } else {
                foreach ($papers as $paperId) {
                    $authorPageRank += $this->paperScores[$paperId];
                }

                $scores[$authorName] = $authorPageRank / count($papers);
            }
        }

        return $scores;
    }
}
