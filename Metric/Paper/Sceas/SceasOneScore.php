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
