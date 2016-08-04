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
use Efrag\Lib\BiblioMetrics\Metric\Paper\ContemporaryHIndexScore;

/**
 * Class ContemporaryHIndex
 * @package Efrag\Lib\BiblioMetrics\Metric\Author
 */
class ContemporaryHIndex extends AuthorMetric
{
    /**
     * This represents an array with as many entries as papers in the Paper-Citation graph. The keys of the array
     * are the paper identifiers and the values the calculated Contemporary h-index score for each of the papers
     * @var array
     */
    protected $paperScores;

    /**
     * Setter method that passes an array of paper scores
     *
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
            isset($this->paperScores) &&
            count($this->paperCitations) == count($this->paperScores);
    }

    /**
     * @return array
     */
    protected function generateScores()
    {
        $authorScores = [];

        foreach ($this->authorPapers as $authorId => $papers) {
            $numPapers = count($papers);

            // Scores in this case are not integer values
            // Add all the scores of the papers in an array and then sort the array so that we end up with an array
            // that has one value for each paper sorted
            $scores = [];
            $distinctScores = [];
            foreach ($papers as $paperId) {
                $score = $this->paperScores[$paperId];

                $scores[] = $score;
                if (!in_array($score, $distinctScores)) {
                    $distinctScores[] = $score;
                }
            }
            sort($scores);
            sort($distinctScores);

            // now loop through that array and try to find the value that will give us the contemporary h-index
            $scoreCounts = [];
            $numDstScores = count($distinctScores);

            for ($i = 0; $i < $numDstScores; $i++) {
                $currentScore = $distinctScores[$i];

                $scoreCounts[$currentScore] = 0;
                for ($j = 0; $j < $numPapers; $j++) {
                    if ($scores[$j] >= $currentScore) {
                        $scoreCounts[$currentScore] += 1;
                    }
                }
            }

            $tmpIndex = 0;
            // now loop through the scoreCounts array and see what is the h-index
            foreach ($scoreCounts as $score => $count) {
                if ($count >= $score) {
                    $tmpIndex = $score;
                }
            }

            $authorScores[$authorId] = $tmpIndex;
        }

        return $authorScores;
    }
}