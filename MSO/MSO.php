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
     * holds an array with the distinct papers that cite directly or indirectly each paper i.e.
     *
     * [
     *      'paper1' => ['paper2', 'paper3'],
     *      'paper2' => ['paper3'],
     * ]
     *
     * @var array
     */
    protected $distinct = [];

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
        $this->distinct = $paperCitations;

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
     * Method to check whether the class has been properly initialized
     * @return boolean
     */
    abstract protected function isInitialized();

    /**
     * This method should be used to initialize the mso property of the class with a structure we are going to use
     * to store the calculated results
     * @return array
     */
    abstract protected function initializeMSO();

    /**
     * This method should be used to populate/calculate the first generation of citations to be used in the calculations
     * for the different MSO tables.
     * @return array
     */
    abstract protected function populateFirstGeneration();

    /**
     * Method that for a particular depth calculates the number of citations received by each of the papers in the
     * Paper-Citation graph
     *
     * @param integer $depth The depth at which to calculate the MSO table
     * @param array $prevGen The array with the previous generation citations
     * @return array
     */
    abstract protected function findPath($depth, array $prevGen);

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

        $prevGen = $this->populateFirstGeneration();

        for ($i = 2; $i <= $this->depth; $i++) {
            $prevGen = $this->findPath($i, $prevGen);
        }

        return $this->mso;
    }
}
