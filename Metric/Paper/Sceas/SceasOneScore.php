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
}
