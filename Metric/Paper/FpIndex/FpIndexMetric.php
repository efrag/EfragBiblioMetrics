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

namespace Efrag\Lib\BiblioMetrics\Metric\Paper\FpIndex;

use Efrag\Lib\BiblioMetrics\Metric\PaperMetric;

/**
 * Class FpIndexMetric
 * @package Efrag\Lib\BiblioMetrics\Metric\Paper\FpIndex
 */
class FpIndexMetric extends PaperMetric
{
    /**
     * A key => value array of the papers included in a Paper-Citation graph that holds the information about the
     * publication year of each paper. This array is used to calculate the current scientific age of a paper that is
     * defined as "(CurrentYear - PublicationYear) - 1"
     *
     * @var array
     */
    protected $paperYear;

    /**
     * The calculated MSO table for the Paper-Citation graph that has been calculated based on the Gs definition for the
     * generations of citations
     *
     * @var array
     */
    protected $msoGs;

    /**
     * The depth at which we wish to generate the fp-index scores for the papers. Providing a depth of 5 for example
     * will generate the fp-index scores for all depths up until and including 5 i.e. 1, 2, 3, 4 and 5.
     * @var int
     */
    protected $depth;

    /**
     * Setter method for the paperYear property of the FpIndexMetric class
     * @param array $paperYear
     * @return $this
     */
    public function setPaperYear(array $paperYear)
    {
        $this->paperYear = $paperYear;

        return $this;
    }

    /**
     * Setter method for the msoGs property of the FpIndexMetric class
     * @param array $msoGs
     * @return $this
     */
    public function setMSOGs(array $msoGs)
    {
        $this->msoGs = $msoGs;

        return $this;
    }

    /**
     * Setter method for the depth property of the FpIndexMetric class
     * @param int $depth
     * @return $this
     */
    public function setDepth($depth)
    {
        if (!is_numeric($depth)) {
            throw new \InvalidArgumentException('The depth needs to be a numeric value');
        }

        $this->depth = (int) $depth;

        return $this;
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @inheritdoc
     */
    protected function isInitialized()
    {
        return (
            isset($this->paperCitations) &&
            isset($this->paperYear) && count($this->paperCitations) == count($this->paperYear) &&
            isset($this->msoGs) && count($this->paperCitations) == count($this->msoGs)
        );
    }

    /**
     * Generate the scores for the fp-index indicator based on the MSOGs table, the Paper-Citation graph and the
     * publication year of each of the papers in the graph.
     *
     * @return array
     * @throws \Exception
     */
    protected function generateScores()
    {
        $scores = [];

        $paperIds = array_keys($this->paperCitations);

        for ($depth = 1; $depth <= $this->depth; $depth++) {
            foreach ($paperIds as $paperId) {
                if (!array_key_exists($paperId, $this->paperYear)) {
                    throw new \Exception('PaperId: ' . $paperId . ': no publication year');
                }

                if (empty($this->paperYear[$paperId]) || !is_numeric($this->paperYear[$paperId])) {
                    throw new \Exception('PaperId: ' . $paperId . ': invalid publication year');
                }

                $divide = 1;

                for ($i = 1; $i <= $depth; $i++) {
                    $divide += $this->msoGs[$paperId][$i] / $i;
                }

                $scores[$paperId][$depth] = $divide / ((date('Y') - $this->paperYear[$paperId]) + 1);
            }
        }

        return $scores;
    }
}
