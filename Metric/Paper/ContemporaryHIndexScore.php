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
    public function isInitialized()
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
