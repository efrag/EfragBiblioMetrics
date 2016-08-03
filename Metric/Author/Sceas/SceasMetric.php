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

namespace Efrag\Lib\BiblioMetrics\Metric\Author\Sceas;

use Efrag\Lib\BiblioMetrics\Metric\AuthorMetric;

/**
 * Class SceasMetric
 * @package Efrag\Lib\BiblioMetrics\Metric\Author\Sceas
 */
abstract class SceasMetric extends AuthorMetric
{

    /**
     * @var \Efrag\Lib\BiblioMetrics\Metric\Paper\Sceas\SceasMetric
     */
    protected $sceasPaperMetric;

    /**
     * @var integer
     */
    protected $topPapers = 25;

    public function __construct()
    {
        $this->sceasPaperMetric = $this->setSceasPaperMetric();
    }

    /**
     * This method needs to be implemented by all classes that inherit this one and it should set the Sceas Paper Metric
     * class to be used in the calculations
     * @return \Efrag\Lib\BiblioMetrics\Metric\Paper\Sceas\SceasMetric
     */
    abstract protected function setSceasPaperMetric();

    /**
     * This method provides a setter for the number of top papers that we should consider in the calculations of Sceas
     * @param $topPapers
     * @return $this
     */
    public function setTopPapers($topPapers)
    {
        if (!is_int($topPapers)) {
            throw new \InvalidArgumentException('The number of top papers should be either an integer');
        }

        $this->topPapers = $topPapers;

        return $this;
    }

    /**
     * @param $factorA
     * @return $this
     */
    public function setFactorA($factorA)
    {
        $this->sceasPaperMetric->setFactorA($factorA);

        return $this;
    }

    /**
     * @param $factorB
     * @return $this
     */
    public function setFactorB($factorB)
    {
        $this->sceasPaperMetric->setFactorB($factorB);

        return $this;
    }

    /**
     * @param $iterations
     * @return $this
     */
    public function setMaxIterations($iterations)
    {
        $this->sceasPaperMetric->setMaxIterations($iterations);

        return $this;
    }

    /**
     * @param $paperReferences
     * @return $this
     */
    public function setPaperReferences($paperReferences)
    {
        $this->sceasPaperMetric->setPaperReferences($paperReferences);

        return $this;
    }

    /**
     * @param array $paperCitations
     * @return $this
     */
    public function setPaperCitations(array $paperCitations)
    {
        parent::setPaperCitations($paperCitations);

        $this->sceasPaperMetric->setPaperCitations($paperCitations);

        return $this;
    }

    /**
     * @return bool
     */
    public function isInitialized()
    {
        return
            isset($this->paperCitations) &&
            isset($this->authorPapers) &&
            isset($this->topPapers) &&
            $this->sceasPaperMetric->isInitialized()
            ;
    }

    /**
     * @return array
     */
    protected function generateScores()
    {
        $paperScores = $this->sceasPaperMetric->getScores();

        $scores = array();

        foreach ($this->authorPapers as $authorId => $papers) {
            $authorValue = 0;

            if (count($papers) > $this->topPapers) {
                $authorPaperScores = [];

                foreach ($papers as $paperId) {
                    $authorPaperScores[] = $paperScores[$paperId];
                }
                rsort($authorPaperScores);

                for ($i = 0; $i < $this->topPapers; $i++) {
                    $authorValue += $authorPaperScores[$i];
                }

                $scores[$authorId] = $authorValue / $this->topPapers;
            } else {
                foreach ($papers as $paperId) {
                    $authorValue += $paperScores[$paperId];
                }

                $scores[$authorId] = $authorValue / count($papers);
            }
        }

        return $scores;
    }
}
