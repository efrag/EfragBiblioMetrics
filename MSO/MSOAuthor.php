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
 * Class MSOAuthor
 * @package Efrag\Lib\BiblioMetrics\MSO
 */
abstract class MSOAuthor extends MSO
{
    /**
     * This is a key => value array that is indexed with the paperID and for each paper stores an array with the author
     * IDs that are the co-authors of each paper.
     * @var array
     */
    protected $paperAuthors;

    /**
     * Method to set the array of papers to authors
     * @param array $paperAuthors
     * @return $this
     */
    public function setPaperAuthors(array $paperAuthors)
    {
        $this->paperAuthors = $paperAuthors;
        return $this;
    }

    /**
     * @return bool
     */
    protected function isInitialized()
    {
        return
            isset($this->paperCitations) &&
            isset($this->depth) &&
            isset($this->paperAuthors) && (count($this->paperCitations) == count($this->paperAuthors))
        ;
    }

    /**
     * Method that initializes the MSO table for the current Paper-Citation graph. The table is a key => value array
     * whose keys are the papers that participate in the Graph. For each paper an array whose keys are the
     * author identifiers of the authors that have co-authored the paper. For each author an array of <depth>
     * elements is created with each element being the total number of citations per generation for each paper
     * for each author i.e.
     *
     * [
     *      'paper1' => [
     *          'author1' => [1 => 2, 2 => 1, 3 => 1],
     *          'author2' => [1 => 2, 2 => 0, 3 => 1]
     *      ],
     * ]
     *
     * So, as we can see from the example above paper1 has two co-authors (author1 and author2) and we have calculated
     * the first three generations of citations for the (paper, author) pair. Author1 has received 2 1-gen citations,
     * 1 2-gen citation and 1 3-gen citation. For author2 we can see that the only difference is that he has not
     * received a 2-gen citation since that was a self-citation and it has been excluded from the calculations.
     *
     * This method though is just used to initialize the structure rather than calculate the results.
     *
     * @return array
     */
    protected function initializeMSO()
    {
        $paperIds = array_keys($this->paperCitations);

        $mso = array_fill_keys($paperIds, []);

        foreach ($this->paperAuthors as $paperId => $authors) {
            foreach ($authors as $authorId) {
                $mso[$paperId][$authorId] = array_fill(1, $this->depth, 0);
            }
        }

        return $mso;
    }

    /**
     * This is a helper method to identify the 1-gen citations for the (paper, author) pairs. In the simple case where
     * we calculate the MSO at the paper level things are easier since the first generation of citations is expected as
     * input via the PaperCitations array, but in this case where we wish to exclude author self-citations we have to
     * calculate the first generation.
     *
     * @return array
     */
    protected function populateFirstGeneration()
    {
        $paperIds = array_keys($this->paperCitations);

        $first = [
            'papers' => $this->paperCitations,
            'authors' => array_fill_keys($paperIds, [])
        ];

        foreach ($this->paperCitations as $paperId => $citations) {
            $first['authors'][$paperId] = array_fill_keys($this->paperAuthors[$paperId], []);

            foreach ($this->paperAuthors[$paperId] as $authorId) {
                foreach ($citations as $citingPaper) {
                    if (!in_array($authorId, $this->paperAuthors[$citingPaper])) {
                        $first['authors'][$paperId][$authorId][] = $citingPaper;
                    }
                }
            }

            foreach ($this->paperAuthors[$paperId] as $authorId) {
                $this->mso[$paperId][$authorId][1] = count(
                    $first['authors'][$paperId][$authorId]
                );
            }
        }

        return $first;
    }
}
