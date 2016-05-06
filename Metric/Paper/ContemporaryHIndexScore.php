<?php

namespace Efrag\Lib\BiblioMetrics\Metric\Paper;

use Efrag\Lib\BiblioMetrics\Metric\PaperMetric;

/**
 * Class ContemporaryHIndexScore
 * @package Efrag\Lib\BiblioMetrics\Metric\Paper
 */
class ContemporaryHIndexScore extends PaperMetric
{
    /**
     * @var integer
     */
    protected $gama = 4;

    /**
     * Key => value array that holds the information about the publication year of each of the papers in the closed set
     * of papers examined. The expected format of this array is ['paperId' => 2016]
     *
     * @var array
     */
    protected $paperAge;

    /**
     * Setter method for the gama parameter used by the Contemporary h-index.
     *
     * @param integer $gama
     * @return $this
     */
    public function setGama($gama)
    {
        if (!is_numeric($gama)) {
            throw new \InvalidArgumentException('The gama should be a numeric value');
        }

        $this->gama = $gama;

        return $this;
    }

    /**
     * Setter method that provides an array with the year of each paper included in the set
     *
     * @param array $paperAge
     * @return $this
     */
    public function setPaperAge(array $paperAge)
    {
        $this->paperAge = $paperAge;

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function isInitialized()
    {
        return (isset($this->paperCitations) && isset($this->gama) && isset($this->paperAge));
    }

    /**
     * @inheritdoc
     */
    protected function generateScores()
    {
        $scores = array();

        foreach ($this->paperCitations as $paperId => $citations) {
            $score = ($this->gama * count($this->paperCitations[$paperId])) / $this->paperAge[$paperId];

            $scores[$paperId] = round($score, $this->decimalPlaces);
        }

        return $scores;
    }
}
