<?php

namespace Efrag\Lib\BiblioMetrics\Metric\Paper;

use Efrag\Lib\BiblioMetrics\Metric\PaperMetric;

/**
 * Class Citations
 * @package Efrag\Lib\BiblioMetrics\Metric\Paper
 */
class Citations extends PaperMetric
{
    /**
     * @inheritdoc
     */
    protected function isInitialized()
    {
        return (isset($this->paperCitations));
    }

    /**
     * @inheritdoc
     */
    protected function generateScores()
    {
        $scores = [];

        foreach ($this->paperCitations as $paperId => $citations) {
            $scores[$paperId] = count($citations);
        }

        return $scores;
    }
}
