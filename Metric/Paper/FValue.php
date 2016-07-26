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

namespace Efrag\Lib\BiblioMetrics\Metric\Paper;

use Efrag\Lib\BiblioMetrics\Metric\PaperMetric;
use Efrag\Lib\BiblioMetrics\MSO\MSOHmDefinition;

/**
 * Class FValue
 * @package Efrag\Lib\BiblioMetrics\Metric\Paper
 */
class FValue extends PaperMetric
{
    /**
     * The number of max iterations we would want to allow for the calculation of the f-value metric
     * @var integer
     */
    protected $maxIterations = 30000;

    /**
     * The reducing factor is calculated per citation graph as described in the original paper
     * @var float
     */
    protected $reducingFactor;

    /**
     * Setter method for the maxIterations property of the class
     *
     * @param $maxIterations
     * @return $this
     */
    public function setMaxIterations($maxIterations)
    {
        if (!is_numeric($maxIterations)) {
            throw new \InvalidArgumentException('The maximum number of iterations should be a numeric value');
        }

        $this->maxIterations = (int) $maxIterations;

        return $this;
    }

    /**
     * Getter for the max iterations
     * @return int
     */
    public function getMaxIterations()
    {
        return $this->maxIterations;
    }

    /**
     * Method that calculates the reducing factor for the provided Paper-Citation graph. In this particular case we are
     * using the Hm Generation definition for calculating the citations received by each paper in each generation.
     *
     * @return float
     * @throws \Exception
     */
    protected function calculateReducingFactor()
    {
        $mso = new MSOHmDefinition();
        $msoTable = $mso->setDepth(2)->setPaperCitations($this->paperCitations)->getMSO();

        $total = [1 => 0, 2 => 0];
        foreach ($msoTable as $record) {
            $total[1] += $record[1];
            $total[2] += $record[2];
        }

        $factor = 1 / ($total[2]/$total[1]);

        return $factor;
    }

    /**
     * This method will need to return the f-value of a paper given the calculated values of the previous iteration.
     *
     * @param $paperId
     * @param $fValues
     * @return int
     */
    protected function sumScore($paperId, $fValues)
    {
        $fSum = 0;

        foreach ($this->paperCitations[$paperId] as $refPaper) {
            $fSum += $fValues[$refPaper];
        }

        return (1 + $this->reducingFactor * $fSum);
    }

    /**
     * @inheritdoc
     */
    public function isInitialized()
    {
        return isset($this->paperCitations) && isset($this->maxIterations);
    }

    /**
     * @return array
     */
    protected function generateScores()
    {
        $this->reducingFactor = $this->calculateReducingFactor();

        $paperIds = array_keys($this->paperCitations);
        $scores = array_fill_keys($paperIds, 1);

        $first      = true;
        $changed    = 0;
        $iterations = 0;

        while (($changed > 0 || $first == true) && $iterations < $this->maxIterations) {
            $first      = false;
            $changed    = 0;
            $prevFValue = $scores;

            foreach ($prevFValue as $paperId => $paperFValueLast) {
                $paperFValueNew = $this->sumScore($paperId, $prevFValue);

                if ($paperFValueNew != $paperFValueLast) {
                    $changed += 1;
                    $scores[$paperId] = $paperFValueNew;
                }
            }

            $iterations += 1;
        }

        return $scores;
    }
}