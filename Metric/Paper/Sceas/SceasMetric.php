<?php

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
}
