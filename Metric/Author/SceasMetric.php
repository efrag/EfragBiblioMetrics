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

namespace Efrag\Lib\BiblioMetrics\Metric\Author\Sceas;

use Efrag\Lib\BiblioMetrics\Metric\AuthorMetric;

/**
 * Class SceasMetric
 * @package Efrag\Lib\BiblioMetrics\Metric\Author\Sceas
 */
class SceasMetric extends AuthorMetric
{
    /**
     * @var integer
     */
    protected $topPapers = 25;

    /**
     * @var array
     */
    protected $paperScores;

    /**
     * This method provides a setter for the number of top papers that we should consider in the calculations of Sceas
     * @param $topPapers
     * @return $this
     */
    public function setTopPapers($topPapers)
    {
        if (!is_int($topPapers)) {
            throw new \InvalidArgumentException('The number of top papers should be either an integer');
        }

        $this->topPapers = $topPapers;

        return $this;
    }

    /**
     * @param array $paperScores
     * @return $this
     */
    public function setPaperScores(array $paperScores)
    {
        $this->paperScores = $paperScores;

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
            isset($this->topPapers) &&
            isset($this->paperScores) &&
            count($this->paperCitations) == count($this->paperScores)
            ;
    }

    /**
     * @return array
     */
    protected function generateScores()
    {
        $scores = array();

        foreach ($this->authorPapers as $authorId => $papers) {
            $authorValue = 0;

            if (count($papers) > $this->topPapers) {
                $authorPaperScores = [];

                foreach ($papers as $paperId) {
                    $authorPaperScores[] = $this->paperScores[$paperId];
                }
                rsort($authorPaperScores);

                for ($i = 0; $i < $this->topPapers; $i++) {
                    $authorValue += $authorPaperScores[$i];
                }

                $scores[$authorId] = $authorValue / $this->topPapers;
            } else {
                foreach ($papers as $paperId) {
                    $authorValue += $this->paperScores[$paperId];
                }

                $scores[$authorId] = $authorValue / count($papers);
            }
        }

        return $scores;
    }
}
