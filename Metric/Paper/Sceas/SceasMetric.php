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

namespace Efrag\Lib\BiblioMetrics\Metric\Paper\Sceas;

use Efrag\Lib\BiblioMetrics\Metric\PaperMetric;

/**
 * Class SceasMetric
 * @package Efrag\Lib\BiblioMetrics\Metric\Paper\Sceas
 */
abstract class SceasMetric extends PaperMetric
{
    /**
     * @var float
     */
    protected $factorA = 2.71828;

    /**
     * @var float
     */
    protected $factorB = 1;

    /**
     * @var integer
     */
    protected $maxIterations = 30000;

    /**
     * @var array
     */
    protected $paperReferences;

    /**
     * @param $factorA
     * @return $this
     */
    public function setFactorA($factorA)
    {
        if (!is_numeric($factorA)) {
            throw new \InvalidArgumentException('Factor A should be either a float or an integer');
        }

        $factorA = (float) $factorA; // convert the numeric value to a float

        $this->factorA = round($factorA, $this->decimalPlaces);

        return $this;
    }

    /**
     * Getter for the FactorA property of the class
     * @return float
     */
    public function getFactorA()
    {
        return $this->factorA;
    }

    /**
     * @param $factorB
     * @return $this
     */
    public function setFactorB($factorB)
    {
        if (!is_numeric($factorB)) {
            throw new \InvalidArgumentException('Factor B should be either a float or an integer');
        }

        $factorB = (float) $factorB; // convert the numeric value to a float

        $this->factorB = round($factorB, $this->decimalPlaces); // use the default number of decimal places

        return $this;
    }

    /**
     * Getter for the FactorB property of the class
     * @return float
     */
    public function getFactorB()
    {
        return $this->factorB;
    }

    /**
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
     * @param array $paperReferences
     * @return $this
     */
    public function setPaperReferences(array $paperReferences)
    {
        $this->paperReferences = $paperReferences;

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function isInitialized()
    {
        return (
            isset($this->paperCitations) &&
            isset($this->factorA) &&
            isset($this->factorB) &&
            isset($this->maxIterations) &&
            isset($this->paperReferences) &&
            (count($this->paperCitations) === count($this->paperReferences))
        );
    }

    /**
     * Method that calculates the summed score of a paper participating in the Paper-Citation graph based on the
     * previous scores of all papers directly citing the current paper. The score uses the formula defined from the
     * SceasOne metric
     *
     * @param string|int    $paper      The current paper index e.g. 5 or paper5
     * @param array         $prevScore  The array that holds the previous SceasOne values
     *
     * @return float|int
     */
    protected function sumScore($paper, $prevScore)
    {
        $score = 0;

        foreach ($this->paperCitations[$paper] as $direct) {
            $numReferences = count($this->paperReferences[$direct]);

            if ($numReferences > 0) {
                $score += (($prevScore[$direct] + $this->factorB) / $numReferences) * (1/$this->factorA);
            }
        }

        return $score;
    }
}
