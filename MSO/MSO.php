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
 * Class MSO
 * @package Efrag\Lib\BiblioMetrics\MSO
 */
abstract class MSO
{
    /**
     * A key => value array whose keys are the papers that participate in a Paper-Citation graph and for each paper it
     * holds an array with all the papers that directly reference the current paper i.e.
     *
     * ['paper1' => ['paper2', 'paper3'], 'paper2' => [], 'paper3' => []]
     *
     * This array indicates that paper1 is referenced by papers paper2 and paper3, whereas papers paper2 and paper3
     * have not yet received any citations
     *
     * @var array
     */
    protected $paperCitations;

    /**
     * A key => value array whose keys are the papers that participate in a Paper-Citation graph and for each paper it
     * holds an array with as many elements as the depth at which we are calculating the MSO table for. For each depth
     * it holds an array of the unique papers that reference the current paper based on the criteria we have specified
     * for each generation i.e.
     *
     * [
     *      'paper1' => [1 => ['paper2', 'paper3'], 2 => ['paper4'], 3 => []],
     *      'paper2' => [1 => [], 2 => [], 3 => []],
     *      'paper3' => [1 => ['paper4'], 2 => [], 3 => []],
     *      'paper4' => [1 => [], 2 => [], 3 => []],
     * ]
     *
     * @var array
     */
    protected $distinct;

    /**
     * The depth at which we wish to calculate the MSO table for the specified Paper-Citation graph
     *
     * @var integer
     */
    protected $depth = 3;

    /**
     * Array that holds the actual calculated MSO table for the Paper-Citation graph provided
     * @var array
     */
    protected $mso;

    /**
     * Setter method for the paperCitations property of the class
     *
     * @param array $paperCitations
     * @return $this
     */
    public function setPaperCitations(array $paperCitations)
    {
        $this->paperCitations = $paperCitations;

        return $this;
    }

    /**
     * Setter method for the depth property of the class
     *
     * @param int $depth
     * @return $this
     */
    public function setDepth($depth)
    {
        if (!is_numeric($depth)) {
            throw new \InvalidArgumentException('The Depth should be an integer value');
        }

        $this->depth = (int) $depth;

        return $this;
    }

    /**
     * Getter method for the depth property of the class
     *
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
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
     * Method to calculate and retrieve the MSO table for a provided Paper-Citation graph. The method first checks
     * whether it has been properly initialized with all required data, then it generates the initial MSO table and
     * for each depth updates the corresponding values
     *
     * @return array
     * @throws \Exception
     */
    public function getMSO()
    {
        if (!$this->isInitialized()) {
            throw new \Exception('The MSO class has not been initialized properly');
        }

        $this->mso = $this->initializeMSO();

        $prevGen = $this->paperCitations;

        for ($i = 2; $i <= $this->depth; $i++) {
            $prevGen = $this->findPath($i, $prevGen);
        }

        return $this->mso;
    }

    /**
     * Method to check whether the class has been properly initialized
     * @return boolean
     */
    abstract protected function isInitialized();

    /**
     * Method that for a particular depth calculates the number of citations received by each of the papers in the
     * Paper-Citation graph
     *
     * @param integer $depth The depth at which to calculate the MSO table
     * @param array $prevGen The array with the previous generation citations
     * @return array
     */
    abstract protected function findPath($depth, array $prevGen);
}
