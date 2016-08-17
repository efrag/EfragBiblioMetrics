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

namespace Efrag\Lib\BiblioMetrics\MSO;

/**
 * Class MSOPaper
 * @package Efrag\Lib\BiblioMetrics\MSO
 */
abstract class MSOPaper extends MSO
{
    /**
     * @return bool
     */
    protected function isInitialized()
    {
        return (isset($this->paperCitations) && isset($this->depth));
    }

    /**
     * Method that initializes the MSO table for the current Paper-Citation graph. The table is a key => value array
     * whose keys are the papers that participate in the Graph. For each paper an array of <depth> elements is created
     * with each element being the total number of citations per generation i.e.
     *
     * [
     *      'paper1' => [1 => 2, 2 => 1, 3 => 0],
     *      'paper2' => [1 => 0, 2 => 0, 3 => 0],
     *      'paper3' => [1 => 1, 2 => 0, 3 => 0],
     *      'paper4' => [1 => 0, 2 => 0, 3 => 0],
     * ]
     *
     * Papers 2 and 4 have received 0 citations in each generation, paper 1 has 2 1-gen citations and a 2-gen citation
     * and finally paper 3 has 1 1-gen citation
     *
     * This method though is just used to initialize the structure rather than calculate the results.
     *
     * @return array
     */
    protected function initializeMSO()
    {
        $paperIds = array_keys($this->paperCitations);

        $mso = array_fill_keys($paperIds, []);

        foreach ($mso as $paperId => $gens) {
            $mso[$paperId] = array_fill(1, $this->depth, 0);
            $mso[$paperId][1] = count($this->paperCitations[$paperId]);
        }

        return $mso;
    }

    /**
     * @return array
     */
    protected function populateFirstGeneration()
    {
        return $this->paperCitations;
    }
}
