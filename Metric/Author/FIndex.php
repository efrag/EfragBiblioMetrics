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
 * Class FIndex
 * @package Efrag\Lib\BiblioMetrics\Metric\Author
 */
class FIndex extends AuthorMetric
{
    /**
     * An array indexed by paperId that represents the list of co-authors of any particular paper. The authors are
     * identified via their authorId
     * @var array
     */
    protected $paperAuthors;

    /**
     * An array indexed by paperId with values equal to the scientific age of each paper.
     * @var array
     */
    protected $paperAge;

    /**
     * An array indexed using the paperId with values equal to the F-Value score for each paper.
     * @var array
     */
    protected $paperScores;

    /**
     * @param array $paperAuthors
     * @return $this
     */
    public function setPaperAuthors(array $paperAuthors)
    {
        $this->paperAuthors = $paperAuthors;
        return $this;
    }

    /**
     * @param array $paperAge
     * @return $this
     */
    public function setPaperAge(array $paperAge)
    {
        $this->paperAge = $paperAge;
        return $this;
    }

    /**
     * Setter method used to provide the array of F-Value paper scores for the current Paper-Citation graph
     * @param array $fValueScores
     * @return $this
     */
    public function setFValueScores(array $fValueScores)
    {
        $this->paperScores = $fValueScores;
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
            (isset($this->paperScores) && count($this->paperScores) == count($this->paperCitations)) &&
            (isset($this->paperAuthors) && count($this->paperAuthors) == count($this->paperCitations)) &&
            (isset($this->paperAge) && count($this->paperAge) == count($this->paperCitations))
            ;
    }

    /**
     * @return array
     */
    protected function generateScores()
    {
        $scores = array();

        foreach ($this->authorPapers as $authorId => $papers) {
            $scores[$authorId] = 0;
            $oldestPaperAge = 1; // let's assume they only published papers this year

            foreach ($papers as $paperId) {
                $scores[$authorId] += $this->paperScores[$paperId]/count($this->paperAuthors[$paperId]);

                if ($this->paperAge[$paperId] > $oldestPaperAge) {
                    $oldestPaperAge = $this->paperAge[$paperId];
                }
            }

            $scores[$authorId] = $scores[$authorId] / $oldestPaperAge;
        }

        return $scores;
    }
}
