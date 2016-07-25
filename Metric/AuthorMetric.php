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

namespace Efrag\Lib\BiblioMetrics\Metric;

/**
 * Class AuthorMetric
 * @package Efrag\Lib\BiblioMetrics\Metric
 */
abstract class AuthorMetric
{
    /**
     * Array of paper identifiers and number of direct citations a paper has received. The format of the array is
     * expected to be ['paperId' => 'number of citations']
     * @var array
     */
    protected $paperCitations;

    /**
     * Array of paper identifiers and metric scores for the provided papers. The format of the array is expected to be
     * ['paperId' => 'score']
     * @var array
     */
    protected $scores;

    /**
     * Default number of decimal places to consider when calculating the scores for the metrics
     * @var int
     */
    protected $decimalPlaces = 5;

    /**
     * This is going to be an array that for each author gives us the list of papers. The papers are identified using
     * their unique identifier, so the format of the array would be ['authorId' => ['paperId1', ..., 'paperIdN']]
     * @var array
     */
    protected $authorPapers;

    /**
     * Setter method for the paperCitations property
     * @param array $paperCitations
     * @return $this
     */
    public function setPaperCitations(array $paperCitations)
    {
        $this->paperCitations = $paperCitations;

        return $this;
    }

    /**
     * Setter method for the number of decimal places to be considered when calculating the resulting scores for the
     * papers included in the set
     * @param $decimalPlaces
     * @return $this
     */
    public function setDecimalPlaces($decimalPlaces)
    {
        if (!is_integer($decimalPlaces)) {
            throw new \InvalidArgumentException('Invalid value provided for the decimal places to be considered');
        }

        $this->decimalPlaces = (int) $decimalPlaces;

        return $this;
    }

    /**
     * Setter method for the papers that each author has co-authored.
     * @param array $authorPapers
     * @return $this
     */
    public function setAuthorPapers(array $authorPapers)
    {
        $this->authorPapers = $authorPapers;

        return $this;
    }

    /**
     * Method that should be called to retrieve the scores for the provided papers. If the caller has not provided the
     * complete list of required parameters this method will throw an exception.
     *
     * @return array
     * @throws \Exception
     */
    public function getScores()
    {
        if (!$this->isInitialized()) {
            throw new \Exception('Metric class has not been initialized properly.');
        }

        $this->scores = $this->generateScores();

        return $this->scores;
    }

    /**
     * This method should return true if the metric class has been initialized with all required parameters for it to
     * be able to generate the scores for the individual authors. This method is overridden by all child classes since
     * each of them might require different parameters to be available.
     *
     * @return bool
     */
    abstract protected function isInitialized();

    /**
     * This method should return a key => value array where the key is the identifier of the author and the value is
     * the score that each metric returns for the specific author
     *
     * @return array
     */
    abstract protected function generateScores();
}
