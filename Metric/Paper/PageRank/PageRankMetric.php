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

use Efrag\Lib\BiblioMetrics\Metric\PaperMetric;

/**
 * Class PageRankMetric
 * @package Efrag\Lib\BiblioMetrics\Metric\Paper\PageRank
 */
abstract class PageRankMetric extends PaperMetric
{
    /**
     * Array that includes one entry for each paper that is a list of papers that the current paper references
     * @var array
     */
    protected $paperReferences;

    /**
     * The number of maximum iterations we would like to allow the algorithm before it terminates
     * @var int
     */
    protected $maxIterations = 30000;

    /**
     * The default factor value defined for PageRank calculations
     * @var float
     */
    protected $factor = 0.85;

    /**
     * The value used to check whether a value for a particular paper has changed between two subsequent iterations
     * @var float
     */
    protected $epsilon = 0.000001;

    /**
     * Setter method for the paperReferences property of the class
     * @param array $paperReferences
     * @return $this
     */
    public function setPaperReferences(array $paperReferences)
    {
        $this->paperReferences = $paperReferences;

        return $this;
    }

    /**
     * Setter method for the maxIterations property of the class
     * @param int $maxIterations
     * @return $this
     */
    public function setMaxIterations($maxIterations)
    {
        if (!is_numeric($maxIterations)) {
            throw new \InvalidArgumentException('MaxIterations needs to be a numeric value');
        }

        $this->maxIterations = (int) $maxIterations;

        return $this;
    }

    /**
     * Getter method for teh maxIterations property of the class
     * @return int
     */
    public function getMaxIterations()
    {
        return $this->maxIterations;
    }

    /**
     * Setter method for the factor property of the class
     * @param float $factor
     * @return $this
     */
    public function setFactor($factor)
    {
        if (!is_numeric($factor)) {
            throw new \InvalidArgumentException('The factor needs to be a numeric value');
        }

        $factor = (float) $factor;

        if ($factor > 1) {
            throw new \InvalidArgumentException('The factor should be less than or equal to 1');
        }

        $this->factor = (float) $factor;

        return $this;
    }

    /**
     * Getter method for the factor property
     * @return float
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * Setter method for the epsilon property of the class
     * @param float $epsilon
     * @return $this
     */
    public function setEpsilon($epsilon)
    {
        if (!is_numeric($epsilon)) {
            throw new \InvalidArgumentException('The epsilon needs to be a numeric value');
        }

        $this->epsilon = (float) $epsilon;

        return $this;
    }

    /**
     * Getter method for the epsilon property of the class
     * @return float
     */
    public function getEpsilon()
    {
        return $this->epsilon;
    }

    /**
     * @return array
     */
    protected function generateScores()
    {
        $numPapers = count($this->paperCitations);
        $paperIds = array_keys($this->paperCitations);

        $scores = array_fill_keys($paperIds, 1 / $numPapers);

        $first      = true;
        $changed    = 0;
        $iterations = 0;

        $randomHop = $this->getRandomHop();

        while (($changed > 0 || $first == true) && $iterations < $this->maxIterations) {
            $first          = false;
            $changed        = 0;
            $prevPageRank   = $scores;
            $extra          = $this->calculateExtraParams($prevPageRank);

            foreach ($prevPageRank as $paperId => $paperPageRankLast) {
                $paperPageRankNew =  $randomHop + $this->factor * $this->sumScore($paperId, $prevPageRank, $extra);

                if (abs($paperPageRankNew - $paperPageRankLast) > $this->epsilon) {
                    $changed += 1;
                    $scores[$paperId] = $paperPageRankNew;
                }
            }

            $iterations += 1;
        }

        return $scores;
    }

    /**
     * This method should return true if the metric class has been initialized with all required parameters for it to
     * be able to generate the scores for the individual papers. This method is overridden by all child classes since
     * each of them might require different parameters to be available.
     *
     * @return bool
     */
    protected function isInitialized()
    {
        return (
            isset($this->paperCitations) &&
            (isset($this->paperReferences) && count($this->paperCitations) == count($this->paperReferences)) &&
            isset($this->maxIterations) &&
            isset($this->factor) && isset($this->epsilon)
        );
    }

    /**
     * This method needs to be implemented in the subclasses and defines how we are calculating the summary score for
     * each publication included in the Paper-Citation graph.
     * @param string|int $paperId   The current paper identifier e.g. 5
     * @param array $prevPageRank   The array that holds the previous PageRank values
     * @param array $params         Array with any extra parameters required to calculate the score
     * @return float|int
     */
    abstract protected function sumScore($paperId, array $prevPageRank, array $params = []);

    /**
     * This method will be implemented in the subclasses to get which constant should be used as the random hop
     * @return float
     */
    abstract protected function getRandomHop();

    /**
     * @param array $prevPageRank
     * @return array
     */
    abstract protected function calculateExtraParams(array $prevPageRank);
}
