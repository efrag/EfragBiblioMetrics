<?php

namespace Efrag\Lib\BiblioMetrics\Metric\Paper\Sceas;

/**
 * Class SceasTwoScore
 * @package Efrag\Lib\BiblioMetrics\Metric\Paper\Sceas
 */
class SceasTwoScore extends SceasMetric
{
    /**
     * @var float
     */
    protected $factorD = 0.85;

    /**
     * Setter for the FactorD property of the class
     * @param int|float $factorD
     * @return $this
     */
    public function setFactorD($factorD)
    {
        if (!is_numeric($factorD)) {
            throw new \InvalidArgumentException('Factor D should be either a float or an integer');
        }

        $factorD = (float) $factorD; // convert the numeric value to a float

        $this->factorD = round($factorD, $this->decimalPlaces);

        return $this;
    }

    /**
     * Getter method for the FactorD property of the class
     * @return float
     */
    public function getFactorD()
    {
        return $this->factorD;
    }

    /**
     * @inheritdoc
     */
    protected function isInitialized()
    {
        return parent::isInitialized() && isset($this->factorD);
    }

    /**
     * @inheritdoc
     */
    protected function generateScores()
    {
        $numPapers  = count($this->paperCitations);
        $paperIds   = array_keys($this->paperCitations);

        $scores = array_fill_keys($paperIds, 1/$numPapers);

        $first      = true;
        $changed    = 0;
        $iterations = 0;

        while (($changed > 0 || $first === true) && $iterations < $this->maxIterations) {
            $first      = false;
            $changed    = 0;
            $prevScores = $scores;

            foreach ($scores as $paperId => $paperLastScore) {
                $paperScoreNew = (1 - $this->factorD) + $this->factorD * $this->sumScore($paperId, $prevScores);

                if ($paperScoreNew != $paperLastScore) {
                    $changed += 1;
                    $scores[$paperId] = $paperScoreNew;
                }
            }

            $iterations += 1;
        }

        return $scores;
    }
}
