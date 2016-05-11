<?php

namespace Efrag\Lib\BiblioMetrics\Metric\Paper\Sceas;

/**
 * Class SceasOneScore
 * @package Efrag\Lib\BiblioMetrics\Metric\Paper\Sceas
 */
class SceasOneScore extends SceasMetric
{
    /**
     * @inheritdoc
     */
    protected function isInitialized()
    {
        return parent::isInitialized();
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
                $paperScoreNew = $this->sumScore($paperId, $prevScores);

                if ($paperScoreNew != $paperLastScore) {
                    $changed += 1;
                    $scores[$paperId] = $paperScoreNew;
                }
            }

            $iterations += 1;
        }

        return $scores;
    }

    /**
     * Method that calculates the summed score of a paper participating in the Paper-Citation graph based on the
     * previous scores of all papers directly citing the current paper. The score uses the formula defined from the
     * SceasOne metric
     *
     * @param int   $paper      The current paper index e.g. 5
     * @param array $prevScore  The array that holds the previous SceasOne values
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
